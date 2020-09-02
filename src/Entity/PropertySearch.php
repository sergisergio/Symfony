<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PropertySearch {

  /**
   * @var int|null
   */
  private $maxPrice;

  /**
   * @var int|null
   * @assert\Range(min=10, max=400)
   */
  private $minSurface;

  /**
   * @var ArrayCollection
   */
  private $options;

  public function __construct()
  {
    $this->options = new ArrayCollection();
  }

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

  /**
   * @return ArrayCollection
   */
  public function getOptions(): ArrayCollection
  {
    return $this->options;
  }

  /**
   * @param ArrayCollection $options
   */
  public function setOptions(ArrayCollection $options): void
  {
    $this->options = $options;
  }
}
