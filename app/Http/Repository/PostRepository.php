<?php

namespace App\Http\Repository;

use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Jobs\ProcessContact;

class PostRepository implements PostRespositoryinterface
{

public function contact($request){
ProcessContact::dispatchSync(
   $request->name,
   $request->email,
   $request->phone,
   $request->subject,
   $request->comment
);
}

}
