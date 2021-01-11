/*jshint esversion: 6 */
jQuery( document ).ready( function( $ ){
	/**
	 * Display coauthors on the frontend.
	 *
	 * @param {node} $authorEl
	 */
    var displayCoAuthors = function( $authorEl ) {
        var authorData = $( '.cx-coa-authors-data' ).data( 'cx_coa_co_authors' );

        if ( undefined === authorData || authorData.length < 1 ) {
            return;
        }

        var html = '';

		authorData.forEach( function( author ) {
			
			authorName = author.name;
			authorLink = author.link;

			html += ', <a href="' + authorLink + '" title="' + authorName + '" rel="author" itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person">'	+ authorName + '</a>';
		} );

		// Add to the "posted by" section 🚀.
		$authorEl.append( html );
	};

	displayCoAuthors( $( '.post-author' ) );
});
