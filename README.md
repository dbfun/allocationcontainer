# Описание

Разбивка массива на блоки по 1, 2 или 3 элемента

* 2 элемента - если у обоих есть картинка (поле $this->key)
* 3 элемента - если у одного нет картинки
* 1 элемент - если всего один элемент

![Сетка](https://github.com/dbfun/allocationcontainer/raw/master/data/preview.jpg)


# Использование

```
$allocationContainer = new AllocationContainer();
$allocationData = $allocationContainer->init()->load($this->itemsRows, 'is_image')->getContainers();

foreach($allocationData as $dataArray) {
  // line
  foreach ($dataArray as $key => $item) {
    // row
  }
}
```

foreach возвращает array с таким блоком. Ключи от 0 до 2.

Другой механизм обращения к элементам не предусмотрен.

Для демонстрации запустить `AllocationData::selfTest();`