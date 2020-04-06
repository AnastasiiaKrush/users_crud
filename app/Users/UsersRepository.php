<?php

namespace App\Users;

use App\User;

interface UsersRepository
{
    /**
     * Search for users.
     *
     * @param string $query
     * @param int $page
     * @param int $count
     * @return array
     */
    public function search(string $query = '', int $page = 0, int $count = 10): array;

    /**
     * Count total number of users in index.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Search for user by id.
     *
     * @param int $id
     * @return array
     */
    public function searchByID(int $id): array;

    /**
     * Save user to index.
     *
     * @param User $user
     */
    public function create(User $user);

    /**
     * Update an existing user in index.
     *
     * @param int $id
     * @param array $updatedUser
     */
    public function update(int $id, array $updatedUser);

    /**
     * Delete specific user from index.
     *
     * @param int $id
     */
    public function delete(int $id);

    /**
     * Get user with max id.
     *
     * @param array $searchable
     * @return array
     */
    public function getOneWithMaxID(array $searchable = ['id']): array;
}
