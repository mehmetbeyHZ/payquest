<?php

namespace App\Policies;

use App\Model\TicketsMessages;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketMessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param  TicketsMessages  $ticketsMessages
     * @return mixed
     */
    public function view(User $user, TicketsMessages $ticketsMessages)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param  TicketsMessages  $ticketsMessages
     * @return mixed
     */
    public function update(User $user, TicketsMessages $ticketsMessages)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param  TicketsMessages  $ticketsMessages
     * @return mixed
     */
    public function delete(User $user, TicketsMessages $ticketsMessages)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param  TicketsMessages  $ticketsMessages
     * @return mixed
     */
    public function restore(User $user, TicketsMessages $ticketsMessages)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param  TicketsMessages  $ticketsMessages
     * @return mixed
     */
    public function forceDelete(User $user, TicketsMessages $ticketsMessages)
    {
        //
    }
}
