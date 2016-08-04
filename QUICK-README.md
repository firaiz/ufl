## Database
table example
```
users
|id|name|valid_flg|created_at|
| 1|   a|    1    |2016-01-02|
| 2|   b|    1    |2016-01-02|
| 3|   c|    0    |2016-01-03|
| 4|   d|    1    |2016-01-05|
| 5|   d|    1    |2016-01-05|
```

### INSERT
```
$instance = Database::getInstance();
$instance->insert('users', array('name' => 'e', 'created_at' => '2016-01-06'));
$instance->insert('users', array('name' => 'f', 'created_at' => '2016-01-06'));
$instance->insert('users', array('name' => 'g', 'created_at' => '2016-01-06'));
$instance->insert('users', array('name' => 'h', 'created_at' => '2016-01-06'));
$instance->insert('users', array('name' => 'i', 'created_at' => '2016-01-06'));
```

### SELECT

##### SQL pattern
```
$instance = Database::getInstance();
$result = $instance->select('SELECT * FROM users WHERE id = :id AND valid_flg = :validFlg', array(':id' => 1, ':validFlg' => 1));

$result = $instance->select('SELECT * FROM users WHERE id IN (:idList) AND valid_flg = :validFlg', array(':idList' => array(1,2,5), ':validFlg' => 1), array(':idList' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
```

##### QueryBuilder pattern
```
$qb = Database::getInstance()->builder();
$qb->from('users')
   ->select('*')
   ->where('id = :id AND valid_flg = :validFlg')
   ->setParam(':id', 1)
   ->setParam(':validFlg', 1)
   ->orderBy('created_at');
// or
$qb->from('users')
    ->select('*')
    ->where('id = :id')
    ->andWhere('valid_flg = :validFlg ')
    ->setParameters(array(':id' => 1, ':validFlg' => 1))
    ->orderBy('created_at');

$request = Request::getInstance();
if (!is_null($request->get('name'))) {
  $qb->addWhere('name = :name')->setParam(':name', $request->get('name', 'default_name'));
}
$qb->addWhere('id in (:idList)')->setParameter(':idList', array(1,2,5), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY);
```

#### Use result
```
// fetch row
$result->fetch()

// fetch rows
$result->fetchAll();
// or iterator
freach ($result as $row) {
  $row['name']
}
```

### UPDATE
```
$instance = Database::getInstance();
$affectedRowCount = $instance->update('users', array('valid_flg' => 0, 'name' => 'f'), array('valid_flg' => 1, 'id' => '5'));
```
result
```
|id|name|valid_flg|created_at|
| 1|   a|    1    |2016-01-02|
| 2|   b|    1    |2016-01-02|
| 3|   c|    0    |2016-01-03|
| 4|   d|    1    |2016-01-05|
| 5|   f|    0    |2016-01-05|
```

### DELETE
```
$instance = Database::getInstance();
$affectedRowCount = $instance->delete('users', array('valid_flg' => 1, 'id' => '5'));
```
result
```
|id|name|valid_flg|created_at|
| 1|   a|    1    |2016-01-02|
| 2|   b|    1    |2016-01-02|
| 3|   c|    0    |2016-01-03|
| 4|   d|    1    |2016-01-05|
```
