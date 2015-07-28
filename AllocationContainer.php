<?
/**
 * Разбивка массива на блоки по 1, 2 или 3 элемента
 * 2 элемента - если у обоих есть картинка (поле $this->key)
 * 3 элемента - если у одного нет картинки
 * 1 элемент - если всего один элемент
 * foreach возвращает array с таким блоком. Ключи от 0 до 2
 * Другой механизм обращения к элементам не предусмотрен
 * Для демонстрации запустить AllocationData::selfTest();
 */

// rewind(next), valid, current, key - порядок обработки при foreach

class AllocationContainer {
  private $containers = array();

  public function init() {
    $this->containers = array();
    return $this;
  }

  public function getContainers() {
    return $this->containers;
  }

  private function put($cntNum, $item) {
    $container =& $this->containers[$cntNum];
    if(count($container) >= 3) return false;
    if(count($container) == 2 && $container[0][$this->keyName] && $container[1][$this->keyName]) return false;
    $container[] = $item;
    return true;
  }

  private function repackTwoLast() {
    $cntCount = count($this->containers);
    if ($cntCount < 2) return;
    $lastContainer =& $this->containers[$cntCount - 1];
    $penultimateContainer =& $this->containers[$cntCount - 2];
    if(count($lastContainer) != 1) return;

    switch(count($penultimateContainer)) {
      case 3:
        $item = array_pop($penultimateContainer);
        $lastContainer[] = $item;
        break;
      case 2:
        $item = array_pop($lastContainer);
        $penultimateContainer[] = $item;
        unset($this->containers[$cntCount - 1]);
        break;
      default:
    }
  }

  private $data, $keyName;
  public function load(array $_data, $keyName) {
    $this->data = array_values($_data);
    $this->keyName = $keyName;

    $step = 0; $cntNum = 0;
    foreach ($this->data as $item) {
      $step++;
      if ($step == 1) {
        $this->put($cntNum, $item);
        } else {
        if (!$this->put($cntNum, $item)) {
          $cntNum++;
          $this->put($cntNum, $item);
          }
        }
      }
    $this->repackTwoLast();
    $this->swapContainerCenter();
    return $this;
  }

  private function swapContainerCenter() {
    foreach($this->containers as &$container) {
      if(count($container) == 3) $container = $this->swapCenter($container, $this->keyName);
    }
  }

  // помещает в центр картинку
  public static function swapCenter($_data, $sortKey) {
    if (count($_data) < 3) return $_data;
    $data = array_values($_data);
    $isFoundedImage = false;
    foreach($data as $key => $item) {
      if ($item[$sortKey]) {
        $isFoundedImage = true;
        break;
      }
    }
    if ($isFoundedImage && $key != 1) { list($data[1], $data[$key]) = array($data[$key], $data[1]); }
    return $data;
  }

  /* Демонстрация */
  public static function selfTest() {
    $sampleData = array(
      array('id' => 0, 'name' => 'test_0', 'is_image' => true), array('id' => 1, 'name' => 'test_1', 'is_image' => true), array('id' => 2, 'name' => 'test_2', 'is_image' => false), array('id' => 3, 'name' => 'test_3', 'is_image' => true), array('id' => 4, 'name' => 'test_4', 'is_image' => true), array('id' => 5, 'name' => 'test_5', 'is_image' => false), array('id' => 6, 'name' => 'test_6', 'is_image' => true), array('id' => 7, 'name' => 'test_7', 'is_image' => false), array('id' => 8, 'name' => 'test_8', 'is_image' => false), array('id' => 9, 'name' => 'test_9', 'is_image' => false), array('id' => 10, 'name' => 'test_10', 'is_image' => false), array('id' => 11, 'name' => 'test_11', 'is_image' => false), array('id' => 12, 'name' => 'test_12', 'is_image' => false), array('id' => 13, 'name' => 'test_13', 'is_image' => false), array('id' => 14, 'name' => 'test_14', 'is_image' => true), array('id' => 15, 'name' => 'test_15', 'is_image' => true), array('id' => 16, 'name' => 'test_16', 'is_image' => true), array('id' => 17, 'name' => 'test_17', 'is_image' => true), array('id' => 18, 'name' => 'test_18', 'is_image' => true), array('id' => 19, 'name' => 'test_19', 'is_image' => false), array('id' => 20, 'name' => 'test_20', 'is_image' => true), array('id' => 21, 'name' => 'test_21', 'is_image' => true), array('id' => 22, 'name' => 'test_22', 'is_image' => true), array('id' => 23, 'name' => 'test_23', 'is_image' => true), array('id' => 24, 'name' => 'test_24', 'is_image' => false), array('id' => 25, 'name' => 'test_25', 'is_image' => true), array('id' => 26, 'name' => 'test_26', 'is_image' => true), array('id' => 27, 'name' => 'test_27', 'is_image' => true), array('id' => 28, 'name' => 'test_28', 'is_image' => false), array('id' => 29, 'name' => 'test_29', 'is_image' => true), array('id' => 30, 'name' => 'test_30', 'is_image' => false)
    );
    $container = new self();

    $container->init()->load($sampleData, 'is_image');
    $container->showKeys();
    $container->showGroups();
  }

  /* Показывается "картина" ключей */
  public function showKeys() {
    foreach ($this->data as $item) {
      echo (int)$item[$this->keyName];
    }
    echo PHP_EOL;
  }

  /* Показывается как сгруппировано */
  public function showGroups() {
    foreach($this->containers as $container) {
      echo count($container);
    }
    echo PHP_EOL;
  }

}


AllocationContainer::selfTest();