<?php
/**
 * BaseModel Class
 * Class cơ bản cho tất cả models
 */

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Lấy tất cả records
     */
    public function all($columns = ['*'])
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table}";
        
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy record theo ID
     */
    public function find($id, $columns = ['*'])
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->primaryKey} = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Lấy record đầu tiên theo điều kiện
     */
    public function where($column, $operator, $value = null, $columns = ['*'])
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$column} {$operator} ?";
        
        return $this->db->fetch($sql, [$value]);
    }
    
    /**
     * Lấy nhiều records theo điều kiện
     */
    public function whereAll($column, $operator, $value = null, $columns = ['*'])
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$column} {$operator} ?";
        
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        
        return $this->db->fetchAll($sql, [$value]);
    }
    
    /**
     * Tạo record mới
     */
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        return $this->db->insert($sql, array_values($data));
    }
    
    /**
     * Cập nhật record
     */
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = array_keys($data);
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        
        $values = array_values($data);
        $values[] = $id;
        
        return $this->db->execute($sql, $values);
    }
    
    /**
     * Xóa record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Đếm số records
     */
    public function count($column = null, $operator = null, $value = null)
    {
        if ($column === null) {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $result = $this->db->fetch($sql);
            return $result['count'];
        }
        
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} {$operator} ?";
        $result = $this->db->fetch($sql, [$value]);
        return $result['count'];
    }
    
    /**
     * Pagination
     */
    public function paginate($page = 1, $perPage = 10, $columns = ['*'])
    {
        $offset = ($page - 1) * $perPage;
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        
        $sql = "SELECT {$columns} FROM {$this->table}";
        
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql);
        $total = $this->count();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Lọc chỉ các field được phép fill
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Ẩn các field không cần thiết
     */
    protected function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        return array_diff_key($data, array_flip($this->hidden));
    }
    
    /**
     * Lấy tất cả records với pagination và search
     */
    public function search($searchTerm, $searchColumns = [], $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($searchColumns) && !empty($searchTerm)) {
            $conditions = [];
            foreach ($searchColumns as $column) {
                $conditions[] = "{$column} LIKE ?";
            }
            $sql .= " WHERE " . implode(' OR ', $conditions);
        }
        
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $params = [];
        if (!empty($searchColumns) && !empty($searchTerm)) {
            $searchPattern = "%{$searchTerm}%";
            $params = array_fill(0, count($searchColumns), $searchPattern);
        }
        
        $data = $this->db->fetchAll($sql, $params);
        $total = $this->count();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Thực thi raw query
     */
    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }
    
    /**
     * Bắt đầu transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->db->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->db->rollback();
    }
}
