/**
 * MyWiki — Front-end script.
 *
 * Two search UIs share the same fetch + render helpers:
 *   1. Inline suggestions dropdown (free) — input in header / hero
 *   2. Command-K modal (Pro) — full-screen panel
 *
 * Plus: mobile menu, helpful Y/N widget, TOC scroll-spy, anchor link icons.
 *
 * No dependencies. Vanilla JS only.
 */
( function () {
	'use strict';

	var MW = window.mywikiData || {};
	var $  = function ( sel, ctx ) { return ( ctx || document ).querySelector( sel ); };
	var $$ = function ( sel, ctx ) { return Array.prototype.slice.call( ( ctx || document ).querySelectorAll( sel ) ); };

	function escapeHtml( str ) {
		return String( str || '' )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' )
			.replace( /'/g, '&#39;' );
	}

	function highlight( text, query ) {
		if ( ! query ) return escapeHtml( text );
		var safe   = escapeHtml( text );
		var tokens = query.replace( /^#/, '' ).split( /\s+/ ).filter( Boolean );
		tokens.forEach( function ( t ) {
			if ( t.length < 2 ) return;
			var re = new RegExp( '(' + t.replace( /[.*+?^${}()|[\]\\]/g, '\\$&' ) + ')', 'gi' );
			safe   = safe.replace( re, '<mark>$1</mark>' );
		} );
		return safe;
	}

	function strFromMW( key, fallback ) {
		return ( MW.strings && MW.strings[ key ] ) ? MW.strings[ key ] : fallback;
	}

	var pendingFetch = null;

	function fetchSearch( query ) {
		if ( pendingFetch && pendingFetch.abort ) {
			try { pendingFetch.abort(); } catch ( e ) {}
		}
		var controller = ( 'AbortController' in window ) ? new AbortController() : null;
		pendingFetch = controller;

		var body = new FormData();
		body.append( 'action', 'mywiki_search' );
		body.append( 'nonce', MW.nonce || '' );
		body.append( 'q', query );

		var fetchOpts = { method: 'POST', body: body, credentials: 'same-origin' };
		if ( controller ) fetchOpts.signal = controller.signal;

		return fetch( MW.ajaxUrl, fetchOpts ).then( function ( r ) { return r.json(); } );
	}

	function renderResultRow( item, query, classBase ) {
		var html  = '';
		html += '<a class="' + classBase + '" href="' + escapeHtml( item.url ) + '">';
		html += '<span class="' + classBase + '-icon" aria-hidden="true">' + ( item.icon || '' ) + '</span>';
		html += '<span class="' + classBase + '-body">';
		html +=   '<span class="' + classBase + '-title">' + highlight( item.title, query ) + '</span>';
		if ( item.excerpt ) {
			html += '<span class="' + classBase + '-excerpt">' + highlight( item.excerpt, query ) + '</span>';
		}
		if ( item.crumbs ) {
			html += '<span class="' + classBase + '-crumbs">' + escapeHtml( item.crumbs ) + '</span>';
		}
		html += '</span>';
		html += '<span class="' + classBase + '-arrow" aria-hidden="true">↵</span>';
		html += '</a>';
		return html;
	}

	function renderGroups( data, query, classBase ) {
		if ( ! data || ! data.groups || ! data.total ) return '';
		var html = '';
		data.groups.forEach( function ( group ) {
			if ( ! group.items || ! group.items.length ) return;
			html += '<div class="' + classBase + '-group">';
			html +=   '<h4 class="' + classBase + '-group-title">' + escapeHtml( group.label ) + ' <span class="' + classBase + '-group-count">' + group.items.length + '</span></h4>';
			html +=   '<ul class="' + classBase + '-group-list">';
			group.items.forEach( function ( item ) {
				html += '<li>' + renderResultRow( item, query, classBase + '-result' ) + '</li>';
			} );
			html +=   '</ul>';
			html += '</div>';
		} );
		return html;
	}

	function setActiveRow( links, index, scrollContainer ) {
		links.forEach( function ( el, i ) {
			el.classList.toggle( 'is-active', i === index );
			if ( i === index && scrollContainer ) {
				var rect  = el.getBoundingClientRect();
				var pRect = scrollContainer.getBoundingClientRect();
				if ( rect.bottom > pRect.bottom ) {
					scrollContainer.scrollTop += ( rect.bottom - pRect.bottom ) + 8;
				} else if ( rect.top < pRect.top ) {
					scrollContainer.scrollTop -= ( pRect.top - rect.top ) + 8;
				}
			}
		} );
	}

	/* ============================================================
	 * INLINE SUGGESTIONS (free theme default)
	 * ============================================================ */
	$$( '[data-mw-suggest]' ).forEach( function ( wrap ) {
		var input = $( '[data-mw-suggest-input]', wrap );
		var panel = $( '[data-mw-suggest-panel]', wrap );
		if ( ! input || ! panel ) return;

		var timer = null;
		var lastQ = '';
		var links = [];
		var idx   = -1;

		function showPanel() {
			panel.hidden = false;
			wrap.classList.add( 'is-open' );
		}
		function hidePanel() {
			panel.hidden = true;
			wrap.classList.remove( 'is-open' );
			idx = -1;
		}

		function paintEmpty( q ) {
			panel.innerHTML =
				'<div class="mw-suggest-empty">' +
					'<p class="mw-suggest-empty-title">' + strFromMW( 'noResults', 'No results' ) + ' <em>"' + escapeHtml( q ) + '"</em></p>' +
					'<p class="mw-suggest-empty-hint">' + strFromMW( 'tryDifferent', 'Try a different keyword.' ) + '</p>' +
				'</div>';
			links = [];
		}

		function paintLoading() {
			panel.innerHTML = '<div class="mw-suggest-loading"><span class="mw-spinner" aria-hidden="true"></span><span>' + strFromMW( 'searching', 'Searching…' ) + '</span></div>';
		}

		function paintResults( data, q ) {
			panel.innerHTML = renderGroups( data, q, 'mw-suggest' ) +
				'<div class="mw-suggest-foot"><span><span class="mw-kbd">↵</span> ' + strFromMW( 'select', 'open' ) + '</span><span><span class="mw-kbd">esc</span> ' + strFromMW( 'close', 'close' ) + '</span></div>';
			links = $$( '.mw-suggest-result', panel );
			idx   = links.length ? 0 : -1;
			setActiveRow( links, idx, panel );
		}

		function run() {
			var q = ( input.value || '' ).trim();
			if ( q === lastQ ) return;
			lastQ = q;

			if ( q.length < ( MW.minLen || 2 ) ) {
				hidePanel();
				return;
			}

			showPanel();
			paintLoading();

			fetchSearch( q )
				.then( function ( res ) {
					if ( ! res || ! res.success || ! res.data || ! res.data.total ) {
						paintEmpty( q );
						return;
					}
					paintResults( res.data, q );
				} )
				.catch( function ( err ) {
					if ( err && err.name === 'AbortError' ) return;
					paintEmpty( q );
				} );
		}

		input.addEventListener( 'input', function () {
			clearTimeout( timer );
			timer = setTimeout( run, 180 );
		} );

		input.addEventListener( 'focus', function () {
			if ( ( input.value || '' ).trim().length >= ( MW.minLen || 2 ) ) {
				showPanel();
			}
		} );

		input.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				hidePanel();
				input.blur();
				return;
			}
			if ( e.key === 'ArrowDown' && links.length ) {
				e.preventDefault();
				idx = ( idx + 1 ) % links.length;
				setActiveRow( links, idx, panel );
			} else if ( e.key === 'ArrowUp' && links.length ) {
				e.preventDefault();
				idx = ( idx - 1 + links.length ) % links.length;
				setActiveRow( links, idx, panel );
			} else if ( e.key === 'Enter' ) {
				if ( idx >= 0 && links[ idx ] ) {
					e.preventDefault();
					window.location.href = links[ idx ].href;
				} else if ( ( input.value || '' ).trim() && MW.searchUrl ) {
					e.preventDefault();
					window.location.href = MW.searchUrl + encodeURIComponent( input.value.trim() );
				}
			}
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! wrap.contains( e.target ) ) hidePanel();
		} );
	} );

	$$( '[data-mw-suggest-mobile]' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var input = $( '.mw-header-search [data-mw-suggest-input]' );
			if ( input ) {
				document.body.classList.add( 'mw-header-search-open' );
				setTimeout( function () { input.focus(); }, 50 );
			}
		} );
	} );

	/* ============================================================
	 * SEARCH MODAL (Pro)
	 * ============================================================ */
	var modal       = $( '#mw-search-modal' );
	var searchInput = $( '#mw-search-input' );
	var resultsBox  = $( '#mw-search-results' );

	if ( modal && searchInput && resultsBox ) {
		var triggers   = $$( '[data-mw-search-trigger]' );
		var modalClose = $$( '[data-mw-search-close]' );
		var modalTimer = null;
		var modalLastQ = '';
		var modalLinks = [];
		var modalIdx   = -1;

		var openModal = function () {
			modal.classList.add( 'is-open' );
			modal.setAttribute( 'aria-hidden', 'false' );
			document.body.classList.add( 'mw-modal-open' );
			setTimeout( function () { searchInput.focus(); }, 50 );
		};
		var closeModal = function () {
			modal.classList.remove( 'is-open' );
			modal.setAttribute( 'aria-hidden', 'true' );
			document.body.classList.remove( 'mw-modal-open' );
			searchInput.blur();
		};

		var modalRenderEmpty = function ( q ) {
			resultsBox.innerHTML =
				'<div class="mw-search-empty">' +
					'<p class="mw-search-empty-title">' + strFromMW( 'noResults', 'No results' ) + ' <em>"' + escapeHtml( q ) + '"</em></p>' +
					'<p class="mw-search-empty-hint">' + strFromMW( 'tryDifferent', 'Try a different keyword.' ) + '</p>' +
				'</div>';
			modalLinks = [];
			modalIdx   = -1;
		};

		var modalRenderInitial = function () {
			resultsBox.innerHTML =
				'<div class="mw-search-empty">' +
					'<p class="mw-search-empty-title">' + strFromMW( 'startTyping', 'Start typing to search…' ) + '</p>' +
					'<p class="mw-search-empty-hint">' + strFromMW( 'tipPrefix', 'Tip: prefix with # to search by tag.' ) + '</p>' +
				'</div>';
			modalLinks = [];
			modalIdx   = -1;
		};

		var modalRenderLoading = function () {
			resultsBox.innerHTML = '<div class="mw-search-loading"><span class="mw-spinner" aria-hidden="true"></span><span>' + strFromMW( 'searching', 'Searching…' ) + '</span></div>';
		};

		var modalRenderResults = function ( data, q ) {
			resultsBox.innerHTML = renderGroups( data, q, 'mw-search' );
			modalLinks = $$( '.mw-search-result', resultsBox );
			modalIdx   = modalLinks.length ? 0 : -1;
			setActiveRow( modalLinks, modalIdx, resultsBox );
		};

		var modalSearch = function ( q ) {
			q = ( q || '' ).trim();
			if ( q === modalLastQ ) return;
			modalLastQ = q;
			if ( q.length < ( MW.minLen || 2 ) ) {
				modalRenderInitial();
				return;
			}
			modalRenderLoading();
			fetchSearch( q )
				.then( function ( res ) {
					if ( ! res || ! res.success || ! res.data || ! res.data.total ) {
						modalRenderEmpty( q );
						return;
					}
					modalRenderResults( res.data, q );
				} )
				.catch( function ( err ) {
					if ( err && err.name === 'AbortError' ) return;
					modalRenderEmpty( q );
				} );
		};

		searchInput.addEventListener( 'input', function () {
			clearTimeout( modalTimer );
			modalTimer = setTimeout( function () { modalSearch( searchInput.value ); }, 180 );
		} );

		modalRenderInitial();

		triggers.forEach( function ( el ) {
			el.addEventListener( 'click', function ( e ) { e.preventDefault(); openModal(); } );
		} );

		modalClose.forEach( function ( el ) {
			el.addEventListener( 'click', closeModal );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( ( e.metaKey || e.ctrlKey ) && ( e.key === 'k' || e.key === 'K' ) ) {
				e.preventDefault();
				openModal();
				return;
			}
			if ( e.key === '/' && document.activeElement === document.body ) {
				e.preventDefault();
				openModal();
				return;
			}
			if ( ! modal.classList.contains( 'is-open' ) ) return;

			if ( e.key === 'Escape' ) {
				e.preventDefault();
				closeModal();
			} else if ( e.key === 'ArrowDown' && modalLinks.length ) {
				e.preventDefault();
				modalIdx = ( modalIdx + 1 ) % modalLinks.length;
				setActiveRow( modalLinks, modalIdx, resultsBox );
			} else if ( e.key === 'ArrowUp' && modalLinks.length ) {
				e.preventDefault();
				modalIdx = ( modalIdx - 1 + modalLinks.length ) % modalLinks.length;
				setActiveRow( modalLinks, modalIdx, resultsBox );
			} else if ( e.key === 'Enter' && modalIdx >= 0 && modalLinks[ modalIdx ] ) {
				e.preventDefault();
				window.location.href = modalLinks[ modalIdx ].href;
			}
		} );
	}

	/* ============================================================
	 * MOBILE MENU
	 * ============================================================ */
	var menuToggle = $( '[data-mw-menu-toggle]' );
	var nav        = $( '#mw-primary-nav' );
	if ( menuToggle && nav ) {
		menuToggle.addEventListener( 'click', function () {
			var isOpen = nav.classList.toggle( 'is-open' );
			menuToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			document.body.classList.toggle( 'mw-nav-open', isOpen );
		} );
	}

	/* ============================================================
	 * "WAS THIS HELPFUL?" WIDGET
	 * ============================================================ */
	$$( '[data-mw-helpful]' ).forEach( function ( widget ) {
		var buttons  = $$( '[data-helpful-vote]', widget );
		var feedback = $( '[data-helpful-feedback]', widget );
		var stored   = false;
		try { stored = !! localStorage.getItem( 'mw_helpful_' + widget.dataset.mwHelpful ); } catch ( e ) {}
		if ( stored && feedback ) {
			feedback.hidden = false;
			buttons.forEach( function ( b ) { b.disabled = true; } );
			return;
		}
		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				buttons.forEach( function ( b ) {
					b.classList.toggle( 'is-selected', b === btn );
					b.disabled = true;
				} );
				if ( feedback ) feedback.hidden = false;
				try { localStorage.setItem( 'mw_helpful_' + widget.dataset.mwHelpful, btn.dataset.helpfulVote ); } catch ( e ) {}
				if ( typeof window.mywikiHelpfulCallback === 'function' ) {
					window.mywikiHelpfulCallback( widget.dataset.mwHelpful, btn.dataset.helpfulVote );
				}
			} );
		} );
	} );

	/* ============================================================
	 * TOC scroll-spy
	 * ============================================================ */
	var tocLinks = $$( '.mw-toc a[href^="#"]' );
	if ( tocLinks.length && 'IntersectionObserver' in window ) {
		var headings = tocLinks
			.map( function ( a ) { return document.getElementById( a.getAttribute( 'href' ).slice( 1 ) ); } )
			.filter( Boolean );
		var byId = {};
		tocLinks.forEach( function ( a ) { byId[ a.getAttribute( 'href' ).slice( 1 ) ] = a; } );
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					tocLinks.forEach( function ( a ) {
						a.classList.toggle( 'is-active', a === byId[ entry.target.id ] );
					} );
				}
			} );
		}, { rootMargin: '0px 0px -70% 0px', threshold: 0 } );
		headings.forEach( function ( h ) { io.observe( h ); } );
	}

	/* ============================================================
	 * Anchor link icons on H2/H3
	 * ============================================================ */
	$$( '.mw-article-content h2[id], .mw-article-content h3[id]' ).forEach( function ( h ) {
		var a       = document.createElement( 'a' );
		a.href      = '#' + h.id;
		a.className = 'mw-anchor-link';
		a.setAttribute( 'aria-label', 'Link to this section' );
		a.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>';
		h.appendChild( a );
	} );

}() );
