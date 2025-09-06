<?php
/**
 * Validator Class
 * Xử lý validation cho form data
 */

class Validator
{
    private $data = [];
    private $errors = [];
    private $rules = [];
    
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    
    /**
     * Set data để validate
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Set rules để validate
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * Validate data theo rules
     */
    public function validate()
    {
        $this->errors = [];
        
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $this->validateField($field, $value, $fieldRules);
        }
        
        return empty($this->errors);
    }
    
    /**
     * Kiểm tra có lỗi validation không
     */
    public function fails()
    {
        return !empty($this->errors);
    }
    
    /**
     * Validate một field cụ thể
     */
    private function validateField($field, $value, $rules)
    {
        $rules = is_string($rules) ? explode('|', $rules) : $rules;
        
        foreach ($rules as $rule) {
            $ruleParts = explode(':', $rule);
            $ruleName = $ruleParts[0];
            $ruleValue = $ruleParts[1] ?? null;
            
            switch ($ruleName) {
                case 'required':
                    if (empty($value)) {
                        $this->addError($field, "Trường {$field} là bắt buộc");
                    }
                    break;
                    
                case 'email':
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addError($field, "Trường {$field} phải là email hợp lệ");
                    }
                    break;
                    
                case 'min':
                    if (!empty($value) && strlen($value) < $ruleValue) {
                        $this->addError($field, "Trường {$field} phải có ít nhất {$ruleValue} ký tự");
                    }
                    break;
                    
                case 'max':
                    if (!empty($value) && strlen($value) > $ruleValue) {
                        $this->addError($field, "Trường {$field} không được vượt quá {$ruleValue} ký tự");
                    }
                    break;
                    
                case 'numeric':
                    if (!empty($value) && !is_numeric($value)) {
                        $this->addError($field, "Trường {$field} phải là số");
                    }
                    break;
                    
                case 'integer':
                    if (!empty($value) && !is_int($value) && !ctype_digit($value)) {
                        $this->addError($field, "Trường {$field} phải là số nguyên");
                    }
                    break;
                    
                case 'date':
                    if (!empty($value) && !strtotime($value)) {
                        $this->addError($field, "Trường {$field} phải là ngày hợp lệ");
                    }
                    break;
                    
                case 'in':
                    $allowedValues = explode(',', $ruleValue);
                    if (!empty($value) && !in_array($value, $allowedValues)) {
                        $this->addError($field, "Trường {$field} phải là một trong: " . implode(', ', $allowedValues));
                    }
                    break;
                    
                case 'unique':
                    // Cần implement kiểm tra unique trong database
                    break;
                    
                case 'confirmed':
                    $confirmField = $field . '_confirmation';
                    if (!empty($value) && $value !== ($this->data[$confirmField] ?? null)) {
                        $this->addError($field, "Trường {$field} không khớp với xác nhận");
                    }
                    break;
                    
                case 'regex':
                    if (!empty($value) && !preg_match($ruleValue, $value)) {
                        $this->addError($field, "Trường {$field} không đúng định dạng");
                    }
                    break;
            }
        }
    }
    
    /**
     * Thêm lỗi
     */
    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    /**
     * Lấy tất cả lỗi
     */
    public function errors()
    {
        return $this->errors;
    }
    
    /**
     * Lấy lỗi của một field
     */
    public function error($field)
    {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Kiểm tra có lỗi không
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
    
    /**
     * Kiểm tra field có lỗi không
     */
    public function hasError($field)
    {
        return isset($this->errors[$field]);
    }
    
    /**
     * Lấy lỗi đầu tiên của field
     */
    public function firstError($field)
    {
        $errors = $this->error($field);
        return $errors[0] ?? null;
    }
    
    /**
     * Lấy tất cả lỗi dưới dạng string
     */
    public function errorsAsString($separator = '<br>')
    {
        $allErrors = [];
        foreach ($this->errors as $field => $errors) {
            $allErrors = array_merge($allErrors, $errors);
        }
        return implode($separator, $allErrors);
    }
    
    /**
     * Sanitize input data
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCSRF($token)
    {
        $sessionToken = Session::get(CSRF_TOKEN_NAME);
        if (!$sessionToken || !$token) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRF()
    {
        if (!Session::has(CSRF_TOKEN_NAME)) {
            Session::set(CSRF_TOKEN_NAME, bin2hex(random_bytes(32)));
        }
        return Session::get(CSRF_TOKEN_NAME);
    }
}
