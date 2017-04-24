<?php

class DebateTree {

	public $input;

	public $parser;

	public $frame;

	static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		$out->addModuleStyles( 'ext.DebateTree.css' );
		$out->addModules( 'ext.DebateTree' );
	}

	static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'argument', 'DebateTree::onArgumentTag' );
		$parser->setHook( 'objection', 'DebateTree::onObjectionTag' );
	}

	static function onArgumentTag( $input, array $args, Parser $parser, PPFrame $frame ) {
		$DebateTree = new DebateTree( $input, $parser, $frame );
		return $DebateTree->render();
	}

	static function onObjectionTag() {
		// Do nothing
		// We handle <objection> tags manually
	}

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
}