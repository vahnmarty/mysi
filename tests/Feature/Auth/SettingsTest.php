<?php

test('settings has placement_test_date', function(){
    $this->seed(SettingsTableSeeder::class);
    expect(settings('placement_test_date'))->toBeString();
});