<?php

namespace App\Users;

use App\User;
use Elasticsearch\Client;

class ElasticsearchRepository implements UsersRepository
{
    /** @var Client */
    private $elasticsearch;

    /**
     * Create an instance of Client.
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Search for users.
     *
     * @param string $query
     * @param int $page
     * @param int $count
     * @return array
     */
    public function search(string $query = '', int $page = 0, int $count = 10): array
    {
        $model = new User;

        $searchParams = [
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'from' => $page * $count,
            'size' => $count,
        ];

        if (!empty($query)) {
            $body = ['body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['name', 'email'],
                        'query' => $query,
                        'operator' => 'OR',
                        'type' => 'phrase_prefix',
                        'max_expansions' => 50
                    ]
                ],
            ]];

        } else {
            $body = ['body' => []];
        }

        $searchParams = array_merge($searchParams, $body);
        $elementsFomIndex = $this->elasticsearch->search($searchParams)['hits']['hits'];
        $users = [];

        foreach ($elementsFomIndex as $user) {
            $users[] = $user['_source'];
        }
        return $users;
    }

    /**
     * Count total number of users in index.
     *
     * @return int
     */
    public function count(): int
    {
        $user = new User;

        $searchParams = [
            'index' => $user->getSearchIndex(),
            'type' => $user->getSearchType()
        ];

        return $this->elasticsearch->count($searchParams)['count'];
    }

    /**
     * Search for user by id.
     *
     * @param int $id
     * @return array
     */
    public function searchByID(int $id): array
    {
        $user = new User;

        $searchParams = [
            'index' => $user->getSearchIndex(),
            'body' => [
                'query' => [
                    'match' => [
                        '_id' => $id
                    ]
                ],
            ]
        ];

        return $this->elasticsearch->search($searchParams)['hits']['hits'][0]['_source'];
    }

    /**
     * Save user to index.
     *
     * @param User $user
     */
    public function create(User $user)
    {
        $this->elasticsearch->index([
            'id' => $user->id,
            'index' => $user->getSearchIndex(),
            'type' => $user->getSearchType(),
            'body' => $user->toSearchArray(),
        ]);
    }

    /**
     * Update an existing user in index.
     *
     * @param int $id
     * @param array $updatedUser
     */
    public function update(int $id, array $updatedUser)
    {
        $user = new User();
        $docFields = [];

        foreach ($updatedUser as $key => $value) {
            $docFields[$key] = $value;
        }

        $this->elasticsearch->update([
            'index' => $user->getSearchIndex(),
            'type' => $user->getSearchType(),
            'id' => $id,
            'body' => [
                'doc' => $docFields
            ]
        ]);
    }

    /**
     * Delete specific user from index.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $user = new User();

        $this->elasticsearch->delete([
            'index' => $user->getSearchIndex(),
            'type' => $user->getSearchType(),
            'id' => $id,
        ]);
    }

    /**
     * Get user with max id.
     *
     * @param array $searchable
     * @return array
     */
    public function getOneWithMaxID(array $searchable = ['id']): array
    {
        $user = new User;

        $searchParams = [
            'index' => $user->getSearchIndex(),
            '_source' => $searchable,
            'body' => [
                'sort' => [
                    'id' => [
                        'order' => 'desc'
                    ]
                ],
                'size' => 1
            ]
        ];

        $searchResult = $this->elasticsearch->search($searchParams);

        return $searchResult['hits']['hits'][0]['_source'];
    }
}
