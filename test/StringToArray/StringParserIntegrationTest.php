<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\StringParser;
use Kata\StringToArray\Result;
use Kata\StringToArray\Header\HeaderParserInterface;
use Kata\StringToArray\Header\EmptyParser;
use Kata\StringToArray\Header\LabelParser;
use Kata\StringToArray\Header\UrlParser;
use Kata\StringToArray\Header\AsciiParser;

/**
 * Tests for StringToArray class.
 */
class StringParserIntegrationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the getArray method.
	 *
	 * @param \Kata\StringToArray\Header\HeaderParserInterface $headerParser
	 * @param string                                     $string
	 * @param array                                      $expectedArray
	 * @param \Kata\StringToArray\Result                 $expectedObject
	 *
	 * @return void
	 *
	 * @dataProvider providerStrings
	 */
	public function testGetArray(HeaderParserInterface $headerParser, $string, array $expectedArray, Result $expectedObject)
	{
		$stringParser = new StringParser($headerParser, $string);
		$result       = $stringParser->getArray();

		$this->assertEquals($expectedArray, $result);
	}

	/**
	 * Tests the getObject method.
	 *
	 * @param \Kata\StringToArray\Header\HeaderParserInterface $headerParser
	 * @param string                                     $string
	 * @param array                                      $expectedArray
	 * @param \Kata\StringToArray\Result                 $expectedObject
	 *
	 * @return void
	 *
	 * @dataProvider providerStrings
	 */
	public function testGetObject(HeaderParserInterface $headerParser, $string, array $expectedArray, Result $expectedObject)
	{
		$stringParser = new StringParser($headerParser, $string);
		$result       = $stringParser->getObject();

		$this->assertInstanceOf('\Kata\StringToArray\Result', $result);
		$this->assertEquals($expectedObject, $result);
	}

	/**
	 * Returns input and result to getArray and getObject tests.
	 *
	 * @return array
	 */
	public function providerStrings()
	{
		$dataSet[0]['string'] = "";
		$dataSet[0]['array']  = array(
			""
		);

		$dataSet[1]['string'] = "singleElement";
		$dataSet[1]['array']  = array(
			"singleElement"
		);

		$dataSet[2]['string'] = "a,b,c";
		$dataSet[2]['array']  = array(
			"a",
			"b",
			"c",
		);

		$dataSet[3]['string'] = "100,982,444,990,1";
		$dataSet[3]['array']  = array(
			"100",
			"982",
			"444",
			"990",
			"1",
		);

		$dataSet[4]['string'] = "Mark,Anthony,marka@lib.de";
		$dataSet[4]['array']  = array(
			"Mark",
			"Anthony",
			"marka@lib.de",
		);

		$dataSet[5]['string'] = "211,22,35\n10,20,33";
		$dataSet[5]['array']  = array(
			0 => array(
				"211",
				"22",
				"35",
			),
			1 => array(
				"10",
				"20",
				"33",
			),
		);

		$dataSet[6]['string'] = "luxembourg,kennedy,44\nbudapest,expo ter,5-7\ngyors,fo utca,9";
		$dataSet[6]['array']  = array(
			0 => array(
				"luxembourg",
				"kennedy",
				"44",
			),
			1 => array(
				"budapest",
				"expo ter",
				"5-7",
			),
			2 => array(
				"gyors",
				"fo utca",
				"9",
			),
		);

		$dataSet[7]['string'] = "#useFirstLineAsLabels\n"
								. "Name,Email,Phone\n"
								. "Mark,marc@be.com,998\n"
								. "Noemi,noemi@ac.co.uk,888";
		$dataSet[7]['array']  = array(
			'labels' => array(
				"Name",
				"Email",
				"Phone",
			),
			'data' => array(
				0 => array(
					"Mark",
					"marc@be.com",
					"998",
				),
				1 => array(
					"Noemi",
					"noemi@ac.co.uk",
					"888",
				),
			),
		);

		$dataSet[8]['string'] = "#useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A\n"
								. "Name,Email,Phone\n"
								. "Mark,marc@be.com,198\n"
								. "Noemi,noemi@ac.co.uk,188";
		$dataSet[8]['array']  = array(
			'labels' => array(
				"Name",
				"Email",
				"Phone",
			),
			'data' => array(
				0 => array(
					"Mark",
					"marc@be.com",
					"198",
				),
				1 => array(
					"Noemi",
					"noemi@ac.co.uk",
					"188",
				),
			),
		);

		$dataSet[9]['string'] = "#useFirstLineAsLabels=0&columnDelimiter=;&lineDelimiter=|\n"
								. "Mark;marc@be.com;298|"
								. "Noemi;noemi@ac.co.uk;288";
		$dataSet[9]['array']  = array(
			0 => array(
				"Mark",
				"marc@be.com",
				"298",
			),
			1 => array(
				"Noemi",
				"noemi@ac.co.uk",
				"288",
			),
		);

		$dataSet[10]['string'] = "%1044010\n"
								. "Name,Email,Phone\n"
								. "Mark,marc@be.com,398\n"
								. "Noemi,noemi@ac.co.uk,388";
		$dataSet[10]['array']  = array(
			'labels' => array(
				"Name",
				"Email",
				"Phone",
			),
			'data' => array(
				0 => array(
					"Mark",
					"marc@be.com",
					"398",
				),
				1 => array(
					"Noemi",
					"noemi@ac.co.uk",
					"388",
				),
			),
		);

		// header parser, string input, expected array
		return array(
			array(
				new EmptyParser($dataSet[0]['string']),
				$dataSet[0]['string'],
				$dataSet[0]['array'],
				new Result($dataSet[0]['array']),
			),
			array(
				new EmptyParser($dataSet[1]['string']),
				$dataSet[1]['string'],
				$dataSet[1]['array'],
				new Result($dataSet[1]['array']),
			),
			array(
				new EmptyParser($dataSet[2]['string']),
				$dataSet[2]['string'],
				$dataSet[2]['array'],
				new Result($dataSet[2]['array']),
			),
			array(
				new EmptyParser($dataSet[3]['string']),
				$dataSet[3]['string'],
				$dataSet[3]['array'],
				new Result($dataSet[3]['array']),
			),
			array(
				new EmptyParser($dataSet[4]['string']),
				$dataSet[4]['string'],
				$dataSet[4]['array'],
				new Result($dataSet[4]['array']),
			),
			array(
				new EmptyParser($dataSet[5]['string']),
				$dataSet[5]['string'],
				$dataSet[5]['array'],
				new Result($dataSet[5]['array']),
			),
			array(
				new EmptyParser($dataSet[6]['string']),
				$dataSet[6]['string'],
				$dataSet[6]['array'],
				new Result($dataSet[6]['array']),
			),
			array(
				new LabelParser($dataSet[7]['string']),
				$dataSet[7]['string'],
				$dataSet[7]['array'],
				new Result($dataSet[7]['array']['data'], $dataSet[7]['array']['labels']),
			),
			array(
				new UrlParser($dataSet[8]['string']),
				$dataSet[8]['string'],
				$dataSet[8]['array'],
				new Result($dataSet[8]['array']['data'], $dataSet[8]['array']['labels']),
			),
			array(
				new UrlParser($dataSet[9]['string']),
				$dataSet[9]['string'],
				$dataSet[9]['array'],
				new Result($dataSet[9]['array']),
			),
			array(
				new AsciiParser($dataSet[10]['string']),
				$dataSet[10]['string'],
				$dataSet[10]['array'],
				new Result($dataSet[10]['array']['data'], $dataSet[10]['array']['labels']),
			),
		);
	}

}
