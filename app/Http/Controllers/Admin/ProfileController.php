<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\ProfileHistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }
    public function create(Request $request)
    {
           // Varidationを行う
      $this->validate($request, Profile::$rules);
      $profiles = new Profile;
      $form = $request->all();

      // データベースに保存する
      $profiles->fill($form);
      $profiles->save();      
      return redirect('admin/profile/create');
    }
    public function edit(Request $request)
    {
         // News Modelからデータを取得する
      $profiles = Profile::find($request->id);
      if (empty($profiles)) {
        abort(504);    
      }
      return view('admin.profile.edit', ['profiles_form' => $profiles]);
    }
    public function update(Request $request)
    {
        // Validationをかける
      $this->validate($request, Profile::$rules);
      // News Modelからデータを取得する
      $profiles = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profiles_form = $request->all();
      
      unset($profiles_form['_token']);
      // 該当するデータを上書きして保存する
      $profiles->fill($profiles_form)->save();
      
        $histories = new ProfileHistory();
        $histories->profile_id = $profiles->id;
        $histories->edited_at = Carbon::now();
        $histories->save();

        return redirect('admin/profile/edit?id=' . $profiles->id);
    }
    public function delete(Request $request)
  {
      // 該当するNews Modelを取得
      $profiles = Profile::find($request->id);
      // 削除する
      $profiles->delete();
      return redirect('admin/profile/');
  }
}
