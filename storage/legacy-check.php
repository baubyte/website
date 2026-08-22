$tables = DB::connection('legacy')->select("SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA='legacy'");
foreach ($tables as $t) {
    $count = DB::connection('legacy')->table($t->TABLE_NAME)->count();
    echo $t->TABLE_NAME . ' = ' . $count . PHP_EOL;
}
exit;
