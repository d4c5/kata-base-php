<?php

namespace Kata\Test\LegacyProduct;

use Kata\LegacyProduct\Product;
use Kata\LegacyProduct\NullProduct;
use Kata\LegacyProduct\ProductDao;

class ProductDaoTest extends \PHPUnit_Framework_TestCase
{
	const TEST_DATABASE_FILE = 'LegacyProductTest.db';

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
		$this->pdo->exec("DROP TABLE IF EXISTS `product`");
		$this->pdo->exec("
			CREATE TABLE `product` (
				`id` INTEGER PRIMARY KEY,
				`ean` varchar(64) default '',
				`name` text default ''
			)"
		);
	}

	/**
	 * Tests the returned product.
	 *
	 * @return void
	 */
	public function testGetByEan()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$product       = new Product();
		$product->id   = 1;
		$product->ean  = '0001';
		$product->name = 'Audi';

		$productDao = new ProductDao($this->pdo);

		$this->assertEquals($product, $productDao->getByEan('0001'));

		$nullProduct = new NullProduct();

		$this->assertEquals($nullProduct, $productDao->getByEan('XXXX'));
	}

	/**
	 * Tests the returned product.
	 *
	 * @return void
	 */
	public function testGetById()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$product       = new Product();
		$product->id   = 1;
		$product->ean  = '0001';
		$product->name = 'Audi';

		$productDao = new ProductDao($this->pdo);

		$this->assertEquals($product, $productDao->getById(1));

		$nullProduct = new NullProduct();

		$this->assertEquals($nullProduct, $productDao->getById(32));
	}

	/**
	 * Tests that the empty EAN.
	 *
     * @expectedException Exception
     */
	public function testCreateWithEmptyEANException()
	{
		$product       = new Product();
		$product->id   = 1;
		$product->name = 'Renault';

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
		$product->id  = 1;
		$product->ean = '0001';

		$productDao = new ProductDao($this->pdo);

		$productDao->create($product);
	}

	/**
	 * Tests creating.
	 *
	 * @return void
	 */
	public function testCreate()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$notUniqueProduct       = new Product();
		$notUniqueProduct->id   = 1;
		$notUniqueProduct->ean  = '0001';
		$notUniqueProduct->name = 'Audi';

		$this->assertFalse($productDao->create($notUniqueProduct));

		$newProduct       = new Product();
		$newProduct->id   = 2;
		$newProduct->ean  = '0002';
		$newProduct->name = 'Seat';

		$this->assertTrue($productDao->create($newProduct));
	}

	/**
	 * Tests that the empty EAN.
	 *
     * @expectedException Exception
	 */
	public function testModifyWithEmptyEANException()
	{
		$product       = new Product();
		$product->id   = 1;
		$product->name = 'Renault';

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
		$product       = new Product();
		$product->id   = 1;
		$product->ean = '0001';

		$productDao = new ProductDao($this->pdo);

		$productDao->modify($product);
	}

	/**
	 * Tests that the EAN is not unique.
	 *
     * @expectedException Exception
	 */
	public function testModifyNotUniqueEanException()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 2, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0002');
		$sth->bindValue(':name', 'Honda');
		$sth->execute();

		$product       = new Product();
		$product->id   = 2;
		$product->ean  = '0001';
		$product->name = 'Honda';

		$productDao = new ProductDao($this->pdo);

		$productDao->modify($product);
	}

	/**
	 * Tests mod.
	 *
	 * @return void
	 */
	public function testModify()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		// Modifies EAN and name.
		$product       = new Product();
		$product->id   = 1;
		$product->ean  = '0002';
		$product->name = 'BMW';

		$this->assertTrue($productDao->modify($product));

		$result = $this->pdo->query("SELECT * FROM `product` WHERE `id` = 1");
		$row    = $result->fetchArray(SQLITE3_ASSOC);

		$this->assertEquals(1, $row['id']);
		$this->assertEquals('0002', $row['ean']);
		$this->assertEquals('BMW', $row['name']);

		// Modifies only name
		$productWithNewName       = new Product();
		$productWithNewName->id   = 1;
		$productWithNewName->ean  = '0002';
		$productWithNewName->name = 'Skoda';

		$this->assertTrue($productDao->modify($productWithNewName));

		$resultWithNewName = $this->pdo->query("SELECT * FROM `product` WHERE `id` = 1");
		$rowWithNewName    = $resultWithNewName->fetchArray(SQLITE3_ASSOC);

		$this->assertEquals(1, $rowWithNewName['id']);
		$this->assertEquals('0002', $rowWithNewName['ean']);
		$this->assertEquals('Skoda', $rowWithNewName['name']);
	}

	/**
	 * Tests that the empty ID.
	 *
     * @expectedException Exception
	 */
	public function testDeleteWithoutIdException()
	{
		$product       = new Product();
		$product->ean  = '0002';
		$product->name = 'BMW';

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
		$product->id   = 'asdasd';
		$product->ean  = '0002';
		$product->name = 'BMW';

		$productDao = new ProductDao($this->pdo);

		$productDao->delete($product);
	}

	/**
	 * Tests deleting.
	 *
	 * @return void
	 */
	public function testDelete()
	{
		$sth = $this->pdo->prepare("INSERT INTO `product` (`id`, `ean`, `name`) VALUES(:id, :ean, :name)");
		$sth->bindValue(':id', 1, SQLITE3_INTEGER);
		$sth->bindValue(':ean', '0001');
		$sth->bindValue(':name', 'Audi');
		$sth->execute();

		$productDao = new ProductDao($this->pdo);

		$product       = new Product();
		$product->id   = 1;
		$product->ean  = '0001';
		$product->name = 'Audi';

		$this->assertTrue($productDao->delete($product));

		$result = $this->pdo->query("SELECT * FROM `product` WHERE `id` = 1");
		$row    = $result->fetchArray(SQLITE3_ASSOC);

		$this->assertFalse($row);
	}

}
