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

		$firstObjectionIndex = strpos( $input, '<objection' );
		if ( $firstObjectionIndex > -1 ) {
			$content = substr( $input, 0, $firstObjectionIndex );
			$content = $parser->recursiveTagParse( $content, $frame );
			$objections = substr( $input, $firstObjectionIndex );
			$objections = $parser->recursiveTagParse( $objections, $frame );
		} else {
			$content = $parser->recursiveTagParse( $input, $frame );
			$objections = '';
		}

		$content = Html::rawElement(
			'div', [
				'class' => 'debatetree-content'
			],
			$content
		);

		$dummyContent = Html::rawElement(
			'div', [
				'class' => 'debatetree-content'
			],
			wfMessage( 'debatetree-dummy' )
		);

		$dummyObjection = Html::rawElement(
			'div', [
				'class' => 'debatetree-dummy'
			],
			$dummyContent
		);

		$objections = Html::rawElement(
			'div', [
				'class' => 'debatetree-objections'
			],
			$objections . $dummyObjection
		);

		$argument = Html::rawElement(
			'div', [
				'class' => 'debatetree-argument'
			],
			$content . $objections
		);

		return $argument;
	}

/*
	public function __construct( $input, $parser, $frame ) {
		$this->input = $input;
		$this->parser = $parser;
		$this->frame = $frame;
	}

	public function render() {
		$node = $this->parser->preprocessToDom( $this->input );
		return $this->getArgument( $node );
	}

	public function getArgument( PPNode_DOM $node ) {
		$text = $node->getFirstChild()->node->nodeValue;
		$text = trim( $text );
		$text = $this->parser->recursiveTagParse( $text, $this->frame );

		$content = Html::rawElement(
			'div', [
				'class' => 'debatetree-content'
			],
			$text
		);
		$objections = Html::rawElement(
			'div', [
				'class' => 'debatetree-objections'
			],
			$this->getObjections( $node )
		);
		$argument = Html::rawElement(
			'div', [
				'class' => 'debatetree-argument'
			],
			$content . $objections
		);
		return $argument;
	}

	public function getObjections( PPNode_DOM $node ) {
		$objections = '';
		$children = $node->getChildrenOfType( 'ext' );
		if ( $children ) {
			for ( $i = 0; $i < $children->node->length; $i++ ) {
				$child = $children->item( $i );
				$parts = $child->splitExt();
				$nameNode = $parts['name']->node;
				$nodeName = $nameNode->nodeValue;
				if ( strtolower( $nodeName ) === 'objection' ) {
					$innerNode = $parts['inner'];
					$objections .= $this->getArgument( $innerNode );
				}
			}
		}
		$objections .= $this->getDummy();
		return $objections;
	}

	public function getDummy() {
		$content = Html::rawElement(
			'div', [
				'class' => 'debatetree-content'
			],
			wfMessage( 'debatetree-dummy' )
		);
		$dummy = Html::rawElement(
			'div', [
				'class' => 'debatetree-dummy'
			],
			$content
		);
		return $dummy;
	}
*/
}