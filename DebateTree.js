var DebateTree = {

	// GETTERS

	getArguments: function () {
		return $( '.debatetree-argument' );
	},

	getObjections: function ( argument ) {
		return $( argument ).children( '.debatetree-argument' );
	},

	getDescendants: function ( argument ) {
		return $( '.debatetree-argument', argument );
	},

	getSustainedObjections: function ( argument ) {
		return DebateTree.getObjections( argument ).filter( DebateTree.isSustained );	
	},

	// FILTERS

	isSustained: function ( index, argument ) {
		return DebateTree.getSustainedObjections( argument ).length ? false : true;
	},

	// DOM MODIFIERS

	/**
	 * Prepend the status to the argument
	 */
	addStatus: function ( index, argument ) {
		var status = DebateTree.isSustained( index, argument ) ? 'sustained' : 'refuted',
			text = mw.message( 'debatetree-' + status );
			span = $( '<span>' ).addClass( 'debatetree-status debatetree-' + status ).text( text );

		$( argument ).prepend( span );
	},

	/**
	 * Append the stats to the argument
	 */
	addCounts: function ( index, argument ) {
		var objectionCount = DebateTree.getObjections( argument ).length,
			sustainedObjectionCount = DebateTree.getSustainedObjections( argument ).length,
			descendantCount = DebateTree.getDescendants( argument ).length,
			text = mw.message( 'debatetree-counts', objectionCount, sustainedObjectionCount, descendantCount ),
			span = $( '<span>' ).addClass( 'debatetree-counts' ).text( text );

		if ( objectionCount ) {
			DebateTree.getObjections( argument ).first().before( span );
		} else {
			$( argument ).append( ' ', span );
		}
	},

	/**
	 * Add a dummy objection to the argument
	 */
	addDummyObjection: function ( index, argument ) {
		var editLink = $( '#ca-edit a' ).attr( 'href' ),
			dummyContent = mw.message( 'debatetree-dummy', editLink ).parse(),
			dummyObjection = $( '<div>' ).addClass( 'debatetree-argument' ).html( dummyContent );

		$( argument ).append( dummyObjection );
	},

	// EVENT HANDLERS

	toggleObjections: function ( event ) {
		DebateTree.getObjections( this ).toggle();
		event.stopPropagation();
	},

	/**
	 * Initialisation script
	 */
	init: function () {
		var arguments = DebateTree.getArguments();
		arguments.each( DebateTree.addStatus );
		arguments.each( DebateTree.addCounts );
		arguments.each( DebateTree.addDummyObjection );
		arguments.click( DebateTree.toggleObjections ).click();
	}
};

$( DebateTree.init );