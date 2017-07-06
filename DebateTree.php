<?php

class DebateTree {

	static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		$out->addModuleStyles( 'ext.DebateTree.css' );
		$out->addModules( 'ext.DebateTree' );
	}

	static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'debatetree', 'DebateTree::render' );
	}

	static function render( $input, array $ARGS, Parser $parser, PPFrame $frame ) {
		return Html::rawElement(
			'div', [
				'class' => 'debatetree'
			],
			$input
		);
	}
}