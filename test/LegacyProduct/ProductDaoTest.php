<?php

namespace Kata\Test\LegacyProduct;

use Kata\LegacyProduct\Product;
use Kata\LegacyProduct\NullProduct;
use Kata\LegacyProduct\ProductDao;

class ProductDaoTest extends \PHPUnit_Framework_TestCase
{
	/** The name of test DB. */
	const TEST_DATABASE_FILE = 'LegacyProductTest.db';

	/** Data of products */
	const PRODUCT_NULL_ID      = 0;
	const PRODUCT_NULL_EAN     = 'XXXX';
	const PRODUCT_NULL_NAME    = 'Null';

	const PRODUCT_AUDI_ID      = 1;
	const PRODUCT_AUDI_EAN     = '0001';
	const PRODUCT_AUDI_NAME    = 'Audi';

	const PRODUCT_RENAULT_ID   = 2;
	const PRODUCT_RENAULT_EAN  = '002';
	const PRODUCT_RENAULT_NAME = 'Renault';

	const PRODUCT_SEAT_ID      = 3;
	const PRODUCT_SEAT_EAN     = '0003';
	const PRODUCT_SEAT_NAME    = 'Seat';

	const PRODUCT_HONDA_ID     = 4;
	const PRODUCT_HONDA_EAN    = '0004';
	const PRODUCT_HONDA_NAME   = 'Honda';

	const PRODUCT_BMW_ID       = 5;
	const PRODUCT_BMW_EAN      = '0005';
	const PRODUCT_BMW_NAME     = 'BMW';

	const PRODUCT_SKODA_ID     = 6;
	const PRODUCT_SKODA_EAN    = '0006';
	const PRODUCT_SKODA_NAME   = 'Skoda';

	const NON_EXISTENT_PRODUCT_ID = 32;
	const INVALID_PRODUCT_ID      = 'asdasd';

	/**
	 * DB connectio object.
	 *
	 * @var SQLite3
	 */
	private $pdo = null;

	/**
	 * Sets DB connection.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->pdo = new \SQLite3('test/LegacyProduct/' . self::TEST_DATABASE_FILE);
		$this->pdo->exec("
			CREATE TABLE `product` (
				`id` INTEGER PRIMARY KEY,
				`ean` varchar(64) default '',
				`name` text default ''
			)"
		);
	}

	/**
	 * Drops product table.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->pdo->exec("DROP TABLE IF EXISTS `product`");
	}

	/**
	 * Tests the returned product.
	 *
	 * @param Product $product
	 *
	 * @return void
	 *
	 * @dataProvider providerProducts
	 */
	public function testGetByEan($product)
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id',   $product->id, SQLITE3_INTEGER);
		$sth->bindValue(':ean',  $product->ean);
		$sth->bindValue(':name', $product->name);
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$this->assertEquals($product, $productDao->getByEan($product->ean));
	}

	/**
	 * Tests the returned product.
	 *
	 * @param Product $product
	 *
	 * @return void
	 *
	 * @dataProvider providerProducts
	 */
	public function testGetById($product)
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', $product->id, SQLITE3_INTEGER);
		$sth->bindValue(':ean', $product->ean);
		$sth->bindValue(':name', $product->name);
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$this->assertEquals($product, $productDao->getById($product->id));
	}

	/**
	 * Returns products.
	 *
	 * @return array
	 */
	public function providerProducts()
	{
		return array(
			array(new Product(self::PRODUCT_AUDI_ID, self::PRODUCT_AUDI_EAN, self::PRODUCT_AUDI_NAME)),
			array(new Product(self::PRODUCT_SEAT_ID, self::PRODUCT_SEAT_EAN, self::PRODUCT_SEAT_NAME)),
			array(new NullProduct()),
		);
	}

	/**
	 * Tests that the empty EAN.
	 *
     * @expectedException Exception
     */
	public function testCreateWithEmptyEanException()
	{
		$product       = new Product();
		$product->id   = self::PRODUCT_AUDI_ID;
		$product->name = self::PRODUCT_AUDI_NAME;

		$productDao = new ProductDao($this->pdo);

		$productDao->create($product);
	}

	/**
	 * Tests that the empty name.
	 *
     * @expectedException Exception
     */
	public function testCreateWithEmptyNameException()
	{
		$product      = new Product();
		$product->id  = self::PRODUCT_AUDI_ID;
		$product->ean = self::PRODUCT_AUDI_EAN;

		$productDao = new ProductDao($this->pdo);

		$productDao->create($product);
	}

	/**
	 * Tests creating.
	 *
	 * @param Product $product
	 *
	 * @return void
	 *
	 * @dataProvider providerProductsToCreating
	 */
	public function testCreate($product)
	{
		$productDao = new ProductDao($this->pdo);

		$this->assertTrue($productDao->create($product));
	}

	/**
	 * Tests creating.
	 *
	 * @param Product $product
	 *
	 * @return void
	 *
	 * @dataProvider providerProductsToCreating
	 */
	public function testCreateFailed($product)
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', $product->id, SQLITE3_INTEGER);
		$sth->bindValue(':ean', $product->ean);
		$sth->bindValue(':name', $product->name);
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$this->assertFalse($productDao->create($product));
	}

	/**
	 * Returns the result of creating and product.
	 *
	 * @return array
	 */
	public function providerProductsToCreating()
	{
		return array(
			array(new Product(self::PRODUCT_AUDI_ID, self::PRODUCT_AUDI_EAN, self::PRODUCT_AUDI_NAME)),
			array(new Product(self::PRODUCT_BMW_ID, self::PRODUCT_BMW_EAN, self::PRODUCT_BMW_NAME)),
		);
	}

	/**
	 * Tests that the empty EAN.
	 *
     * @expectedException Exception
	 */
	public function testModifyWithEmptyEanException()
	{
		$product       = new Product();
		$product->id   = self::PRODUCT_AUDI_ID;
		$product->name = self::PRODUCT_AUDI_NAME;

		$productDao = new ProductDao($this->pdo);

		$productDao->modify($product);
	}

	/**
	 * Tests that the empty name.
	 *
     * @expectedException Exception
	 */
	public function testModifyWithEmptyNameException()
	{
		$product      = new Product();
		$product->id  = self::PRODUCT_AUDI_ID;
		$product->ean = self::PRODUCT_AUDI_EAN;

		$productDao = new ProductDao($this->pdo);

		$productDao->modify($product);
	}

	/**
	 * Tests that the EAN is not unique.
	 *
     * @return void
	 */
	public function testModifyNotUniqueEan()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id',   self::PRODUCT_AUDI_ID, SQLITE3_INTEGER);
		$sth->bindValue(':ean',  self::PRODUCT_AUDI_EAN);
		$sth->bindValue(':name', self::PRODUCT_AUDI_NAME);
		$sth->execute();

		$product       = new Product();
		$product->id   = self::PRODUCT_HONDA_ID;
		$product->ean  = self::PRODUCT_AUDI_EAN;
		$product->name = self::PRODUCT_HONDA_NAME;

		$productDao = new ProductDao($this->pdo);

		$this->assertFalse($productDao->modify($product));
	}

	/**
	 * Tests mod.
	 *
	 * @param Product $originalProduct
	 * @param Product $modifiedProduct
	 *
	 * @return void
	 *
	 * @dataProvider providerProductsToModifying
	 */
	public function testModify($originalProduct, $modifiedProduct)
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id',   $originalProduct->id, SQLITE3_INTEGER);
		$sth->bindValue(':ean',  $originalProduct->ean);
		$sth->bindValue(':name', $originalProduct->name);
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$this->assertTrue($productDao->modify($modifiedProduct));

		$sthCheck = $this->pdo->prepare("SELECT * FROM `product` WHERE `id` = :id");
		$sthCheck->bindValue(':id', $modifiedProduct->id);
		$resultCheck = $sthCheck->execute();

		$row = $resultCheck->fetchArray(SQLITE3_ASSOC);

		$this->assertEquals($modifiedProduct->id,   $row['id']);
		$this->assertEquals($modifiedProduct->ean,  $row['ean']);
		$this->assertEquals($modifiedProduct->name, $row['name']);
	}

	/**
	 * Returns the original and modified products.
	 *
	 * @return array
	 */
	public function providerProductsToModifying()
	{
		return array(
			array(
				new Product(self::PRODUCT_AUDI_ID, self::PRODUCT_AUDI_EAN, self::PRODUCT_AUDI_NAME),
				new Product(self::PRODUCT_AUDI_ID, self::PRODUCT_BMW_EAN, self::PRODUCT_BMW_NAME),
			),
			array(
				new Product(self::PRODUCT_SEAT_ID, self::PRODUCT_SEAT_EAN, self::PRODUCT_SEAT_NAME),
				new Product(self::PRODUCT_SEAT_ID, self::PRODUCT_SEAT_EAN, self::PRODUCT_HONDA_NAME),
			),
		);
	}

	/**
	 * Tests that the empty ID.
	 *
     * @expectedException Exception
	 */
	public function testDeleteWithoutIdException()
	{
		$product       = new Product();
		$product->ean  = self::PRODUCT_SKODA_EAN;
		$product->name = self::PRODUCT_SKODA_NAME;

		$productDao = new ProductDao($this->pdo);

		$productDao->delete($product);
	}

	/**
	 * Tests that the empty is not integer.
	 *
     * @expectedException Exception
	 */
	public function testDeleteNoIntegerIdException()
	{
		$product       = new Product();
		$product->id   = self::INVALID_PRODUCT_ID;
		$product->ean  = self::PRODUCT_SKODA_EAN;
		$product->name = self::PRODUCT_SKODA_NAME;

		$productDao = new ProductDao($this->pdo);

		$productDao->delete($product);
	}

	/**
	 * Tests deleting.
	 *
	 * @return void
	 *
	 * @dataProvider providerProductsToDeleting
	 */
	public function testDelete($product)
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id',   $product->id, SQLITE3_INTEGER);
		$sth->bindValue(':ean',  $product->ean);
		$sth->bindValue(':name', $product->name);
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$this->assertTrue($productDao->delete($product));

		$sthCheck = $this->pdo->prepare("SELECT * FROM `product` WHERE `id` = :id");
		$sthCheck->bindValue(':id', $product->id, SQLITE3_INTEGER);
		$resultCheck = $sthCheck->execute();

		$row = $resultCheck->fetchArray(SQLITE3_ASSOC);

		$this->assertFalse($row);
	}

	/**
	 * Returns products.
	 *
	 * @return array
	 */
	public function providerProductsToDeleting()
	{
		return array(
			array(new Product(self::PRODUCT_AUDI_ID, self::PRODUCT_AUDI_EAN, self::PRODUCT_AUDI_NAME)),
			array(new Product(self::PRODUCT_SEAT_ID, self::PRODUCT_SEAT_EAN, self::PRODUCT_SEAT_NAME)),
		);
	}

}
