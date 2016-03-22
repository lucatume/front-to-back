<?php

namespace FTB\Nodes;


class ProcessorTestCase extends \Codeception\TestCase\WPTestCase{

	/**
	 * @var \FTB_Nodes_DOMNodeInterface
	 */
	protected $node;
	/**
	 * @var \FTB_Output_TemplateTagsInterface
	 */
	protected $template_tags;

	/**
	 * @var \FTB_Fields_TransportInterface
	 */
	protected $transport;
	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->node          = $this->prophesize( 'FTB_Nodes_DOMNodeInterface' );
		$this->template_tags = $this->prophesize( 'FTB_Output_TemplateTagsInterface' );
		$this->config        = $this->prophesize( 'FTB_Fields_ConfigDumperInterface' );
		$this->transport     = $this->prophesize( 'FTB_Fields_TransportInterface' );

		$this->node->nodeValue()->willReturn('some-value');
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}
}