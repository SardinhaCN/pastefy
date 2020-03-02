<?php
namespace databases;

use modules\uloleorm\Table;
class PasteTable extends Table {

    public $id,  
           $title,
           $content,
           $created,
           $password = "",
           $userid = 0,
           $encrypted = 0,
           $folder = 0;
    
    public function database() {
        $this->_table_name_ = "paste";
        $this->__database__ = "main";
    }

}
