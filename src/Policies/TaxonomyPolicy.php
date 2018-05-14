<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 5/14/18
 * Time: 2:26 PM
 */

namespace Jlab\Taxonomy\Policies;

use Jlab\Taxonomy\Vocabulary;
use Jlab\Taxonomy\Term;
use Illuminate\Contracts\Auth\Access\Authorizable as User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxonomyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vocabulary.
     *
     * @param  User $user
     * @param Vocabulary            $vocabulary
     * @return mixed
     */
    public function view(User $user, Vocabulary $vocabulary)
    {
        return true;
    }

    /**
     * Determine whether the user can create vocabularies.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isset($user);
    }

    /**
     * Determine whether the user can update the vocabulary.
     *
     * @param  User $user
     * @param Vocabulary            $vocabulary
     * @return mixed
     */
    public function update(User $user, Vocabulary $vocabulary)
    {
        return isset($user);
    }

    /**
     * Determine whether the user can delete the vocabulary.
     *
     * @param  User $user
     * @param Vocabulary            $vocabulary
     * @return mixed
     */
    public function delete(User $user, Vocabulary $vocabulary)
    {
        return isset($user);
    }
}

