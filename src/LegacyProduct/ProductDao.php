<?php

namespace Kata\LegacyProduct;

/**
 * Class ProductDao
 */
class ProductDao
{
	/**
	 * SQL connection
	 *
	 * @var SQLite3
	 */
	private $pdo = null;

	/**
	 * Sets db connection.
	 *
	 * @param SQLite3 $pdo
	 *
	 * @return void
	 */
	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

    /**
     * Get product by EAN.
     *
     * @param string $ean
	 *
     * @return NullProduct|Product
     */
    public function getByEan($ean)
    {
		$sth = $this->pdo->prepare("SELECT * FROM `product` WHERE `ean` = :ean");
		$sth->bindValue(':ean', $ean);
		$result = $sth->execute();

		if (($row = $result->fetchArray(SQLITE3_ASSOC)) !== false)
		{
			$product       = new Product();
			$product->id   = $row['id'];
			$product->name = $row['name'];
			$product->ean  = $row['ean'];
		}
		else
		{
			$product = new NullProduct();
		}

		return $product;
    }

    /**
     * Get product by id.
     *
     * @param int $id
	 *
     * @return NullProduct|Product
     */
    public function getById($id)
    {
		$sth = $this->pdo->prepare("SELECT * FROM `product` WHERE `id` = :id");
		$sth->bindValue(':id', $id, SQLITE3_INTEGER);
		$result = $sth->execute();

		if (($row = $result->fetchArray(SQLITE3_ASSOC)) !== false)
		{
			$product       = new Product();
			$product->id   = $row['id'];
			$product->name = $row['name'];
			$product->ean  = $row['ean'];
		}
		else
		{
			$product = new NullProduct();
		}

		return $product;
    }

    /**
     * Create product in database if the EAN is not existing.
     *
     * @param Product $product
	 *
     * @return bool
     */
    public function create(Product $product)
    {
		$isSuccess = false;

		if (empty($product->ean))
		{
			throw new \Exception(__METHOD__ . " - The EAN is required!");
		}
		if (empty($product->name))
		{
			throw new \Exception(__METHOD__ . " - The name is required!");
		}

		if ($this->checkUnique($product->ean))
		{
			$sth = $this->pdo->prepare("
				INSERT INTO `product`
					(`ean`, `name`)
				VALUES
					(:ean, :name)"
			);

			$sth->bindValue(':ean', $product->ean);
			$sth->bindValue(':name', $product->name);

			$result = $sth->execute();
			if ($result !== false)
			{
				$isSuccess = true;
			}
		}

		return $isSuccess;
    }

    /**
     * Modify the product name and ean in database by id.
     * It checks if the EAN already exists by another product, and does not overwrite.
     *
     * @param Product $product
	 *
     * @return bool
     */
    public function modify(Product $product)
    {
		$isModified = false;

		if (empty($product->ean))
		{
			throw new \Exception(__METHOD__ . " - The EAN is required!");
		}
		if (empty($product->name))
		{
			throw new \Exception(__METHOD__ . " - The name is required!");
		}

		$oldProduct = $this->getById($product->id);
		if ($oldProduct->ean == $product->ean || $this->checkUnique($product->ean))
		{
			$sth = $this->pdo->prepare("
				UPDATE
					`product`
				SET
					`ean` = :ean,
					`name` = :name
				WHERE
					`id` = :id"
			);

			$sth->bindValue(':ean', $product->ean);
			$sth->bindValue(':name', $product->name);
			$sth->bindValue(':id', $product->id, SQLITE3_INTEGER);

			$result = $sth->execute();
			if ($result !== false)
			{
				$isModified = true;
			}
		}

		return $isModified;
    }

    /**
     * Delete product from database
     *
     * @param Product $product
	 *
     * @return bool
     */
    public function delete(Product $product)
    {
		$isDeleted = false;

		if (empty($product->id))
		{
			throw new \Exception(__METHOD__ . " - The id is required!");
		}
		if (!is_int($product->id))
		{
			throw new \Exception(__METHOD__ . " - The id is not integer!");
		}

		$sth = $this->pdo->prepare("DELETE FROM `product` WHERE `id` = :id");
		$sth->bindValue(':id', $product->id, SQLITE3_INTEGER);

		$result = $sth->execute();
		if ($result !== false)
		{
			$isDeleted = true;
		}

		return $isDeleted;
    }

    /**
     * Check if the product will be unique by EAN
     *
     * @param string $ean
	 *
     * @return bool
     */
    private function checkUnique($ean)
    {
		$isUnique = true;

		$sth = $this->pdo->prepare("SELECT COUNT(*) AS cnt FROM `product` WHERE `ean` = :ean");
		$sth->bindValue(':ean', $ean);
		$result = $sth->execute();

		if (
			($countRow = $result->fetchArray(SQLITE3_ASSOC)) !== false
			&& $countRow['cnt'] > 0
		) {
			$isUnique = false;
		}

		return $isUnique;
    }

}
