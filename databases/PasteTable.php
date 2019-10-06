<?php
namespace databases;

use modules\uloleorm\Table;
class PasteTable extends Table {

    public $id,  
           $title,
           $content,
           $created,
           $password,
           $userid,
           $encrypted,
           $folder;
    
    public function database() {
        $this->_table_name_ = "paste";
        $this->__database__ = "main";
    }

}
