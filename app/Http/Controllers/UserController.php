use App\Models\User;

public function index() {
      $users = User::all();
      return json_encode($users);
    }