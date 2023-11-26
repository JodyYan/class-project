<?php

use App\Models\Consultant;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('get consultants', function () {
    $this->consultantsCollection = Consultant::factory()->create();
    $response = $this->get('/api/consultants');
    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getContent())->json()
        ->toHaveCount($this->consultantsCollection->count());
});

it('can create a consultant', function () {
    $attributes = Consultant::factory()->raw(); 
    $response = $this->postJson('/api/consultant', $attributes); //建立新顧問
    $response->assertStatus(201)->assertJson(['result' => 'ok']); //預期收到 API 的成功回覆
    $this->assertDatabaseHas('consultants', ['email' => $attributes['email']]); //assert 是否有建立的新顧問
});

it('consultant can login', function () {
    $consultant = Consultant::factory()->create();
    $credential = [
        'account' => $consultant->email,
        'password' => '123456',
    ];
    $response = $this->postJson('/api/consultant_login', $credential); //創建的顧問可登入並取得資料

    $data = [
        'account' => $consultant->email,
        'name' => $consultant->name,
        'nationality' => $consultant->nationality,
        'introduction' => $consultant->introduction,
    ];
    $response->assertStatus(200)->assertJson(['result' => $data]);
});