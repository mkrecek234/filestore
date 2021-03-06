<?php

namespace atk4\filestore\Model;

class File extends \atk4\data\Model {
    public $table = 'filestore_file';

    public $title_field = 'meta_filename';

    public $flysystem = null;


    function init(): void
    {
        parent::init();

        $this->addField('token', ['system'=>true, 'type' => 'string']);
        $this->addField('location');
        $this->addField('url');
        $this->addField('storage');
        $this->hasOne('source_file_id', new self());

        $this->addField('status', ['enum'=>['draft','uploaded','thumbok','normalok','ready','linked'], 'default'=>'draft']);

        $this->addField('meta_filename');
        $this->addField('meta_extension');
        $this->addField('meta_md5');
        $this->addField('meta_mime_type');
        $this->addField('meta_size', ['type'=>'integer']);
        $this->addField('meta_is_image', ['type'=>'boolean']);
        $this->addField('meta_image_width', ['type'=>'integer']);
        $this->addField('meta_image_height', ['type'=>'integer']);

        $this->onHook(\atk4\data\Model::HOOK_BEFORE_DELETE, function($m) {

            if ($m->flysystem) {
                $m->flysystem->delete($m->get('location'));
            }
        });
    }

    public function newFile()
    {
        $this->unload();

        $this->set('token', uniqid('token-'));
        $this->set('location', uniqid('file-'));
    }
}
