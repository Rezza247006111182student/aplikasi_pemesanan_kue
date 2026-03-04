<?php
class BaseApiController {
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    protected function error($message, $status = 400) {
        $this->json(['error' => $message], $status);
    }
}
