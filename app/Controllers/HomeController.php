<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/KueModel.php';

class HomeController extends BaseController {
    public function index() {
        $model = new KueModel();
        $kue = $model->getAll(50, 0);
        foreach ($kue as &$item) {
            $item['harga_formatted'] = number_format($item['harga'], 0, ',', '.');
        }
        unset($item);
        $this->render('home', ['kueList' => $kue]);
    }
}
