<?php

namespace App\Http\Middleware;

use App\Models\Ticket;
use Closure;
use Illuminate\Http\Request;

class CanAccessTicket
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ticket = $request->route('ticket');
        if (!(
            has_all_permissions(auth()->user(), 'view-all-tickets')
            ||
            (
                has_all_permissions(auth()->user(), 'view-own-tickets')
                && in_array(auth()->user()->id, [$ticket->owner_id, $ticket->responsible_id])
            )
        )) {
            return redirect()->to(route('tickets'));
        }
        return $next($request);
    }
}
