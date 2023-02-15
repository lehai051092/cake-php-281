<?php

class Posts extends CakeMigration
{
    private $mockData = array(
        array(
            'title' => 'The title',
            'body' => 'This is the post body.',
        ),
        array(
            'title' => 'A title once again',
            'body' => 'And the post body follows.',
        ),
        array(
            'title' => 'Title strikes back',
            'body' => 'This is really exciting! Not.',
        ),
    );

    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'posts';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'posts' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
                    'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
                    'body' => array('type' => 'text', 'null' => false, 'default' => NULL),
                    'created' => array('type' => 'datetime'),
                    'modified' => array('type' => 'datetime'),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'posts'
            ),
        ),
    );

    /**
     * Before migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function after($direction)
    {
        $Post = ClassRegistry::init('Post');
        if ($direction === 'up') {
            $this->insertDataPorts($Post);
        }

        return true;
    }

    /**
     * @param $Post
     */
    private function insertDataPorts($Post)
    {
        $data = array();

        foreach ($this->mockData as $key => $item) {
            $data[$key]['Post']['title'] = $item['title'];
            $data[$key]['Post']['body'] = $item['body'];
            $data[$key]['Post']['created'] = date('Y-m-d H:i:s', time());
            $data[$key]['Post']['modified'] = date('Y-m-d H:i:s', time());
        }

        $Post->create();
        if ($Post->saveAll($data)) {
            $this->callback->out('posts table has been initialized');
        }
    }
}
