<?php

$tablename = "todo";

try {
    $db = new PDO("mysql:dbname=$dbname;host=$host;CHARSET=utf8;COLLATE=utf8_unicode_ci", $user, $pass);
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $db->exec("CREATE TABLE IF NOT EXISTS $tablename(
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
)  ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;");
    print("Created $tablename Table success.\n");
} catch(PDOException $e) {
    echo $e->getMessage();
}
