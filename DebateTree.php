<?php

class DebateTree {

	static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		$out->addModuleStyles( 'ext.DebateTree.css' );
		$out->addModules( 'ext.DebateTree' );
	}

	static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'argument', 'DebateTree::renderArgument' );
		$parser->setHook( 'objection', 'DebateTree::renderArgument' );
	}

	static function renderArgument( $input, array $args, Parser $parser, PPFrame $frame ) {

		$content = $parser->recursiveTagParse( $input, $frame );

		return Html::rawElement(
			'div', [
				'class' => 'debatetree-argument'
			],
			$content
		);
	}
}