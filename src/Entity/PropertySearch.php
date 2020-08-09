<?php

namespace App\Entity;

class PropertySearch {

  /**
   * @var int|null
   */
  private $maxPrice;

  /**
   * @var int|null
   */
  private $minSurface;

  /**
   * @return int|null
   */
  public function getMaxPrice()
  {
      return $this->maxPrice;
  }

  /**
   * @param int|null $maxPrice
   * @return PropertySearch
   */
  public function setMaxPrice(int $maxPrice): PropertySearch
  {
      $this->maxPrice = $maxPrice;
      return $this;
  }

  /**
   * @return int|null
   */
  public function getMinSurface()
  {
      return $this->minSurface;
  }

  /**
   * @param  int|null $minSurface
   * @return PropertySearch
   */
  public function setMinSurface(int $minSurface): PropertySearch
  {
      $this->minSurface = $minSurface;
      return $this;
  }
}
