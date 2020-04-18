<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');

        App::get_ci()->load->library('form_validation');

        if (is_prod()) {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id)
    { // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);
        if (empty($post_id)) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    /**
     * Store a comment to the DB.
     *
     * @return mixed
     * @throws Exception
     */
    public function store_comment()
    { // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )

        $post_id = App::get_ci()->input->post('postID', TRUE);
        $message = App::get_ci()->input->post('message', TRUE);
        $assignComment = App::get_ci()->input->post('assignComment', TRUE);
        $assignComment = ($assignComment == "null") ? NULL : $assignComment;

        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post_id = intval($post_id);
        if (empty($post_id) || empty($message)) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $user = User_model::get_user();
        $data = array(
            'user_id' => $user->get_id(),
            'assign_id' => $post_id,
            'assign_comment_id' => $assignComment,
            'text' => $message,
            'time_created' => date('Y-m-d h:i:s')
        );

        try {
            $comment = Comment_model::create($data);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }

        $posts = Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    /**
     * Handle a login request to the application.
     *
     * @return object|string|void
     */
    public function login()
    {
        $email = App::get_ci()->input->post('login', TRUE);
        $password = App::get_ci()->input->post('password', TRUE);

        $rules = array(
            array(
                'field' => 'login',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|alpha_dash'
            )
        );
        App::get_ci()->form_validation->set_rules($rules);

        if (App::get_ci()->form_validation->run() == TRUE) {
            try {
                // If the validation was successful, we try to find the user in the database.
                $user = User_model::find_user($email);
            } catch (EmeraldModelLoadException $exception) {
                return $this->response_error($exception->getMessage());
            }
            // Checking password
            if (isset($user) && ($user->get_password() === $password)) {
                try {
                    Login_model::start_session($user->get_id());
                } catch (Exception $exception) {
                    return $this->response_error($exception->getMessage());
                }
            } else {
                return $this->response_error('These credentials do not match our records');
            }

        } else {
            return $this->response_error(validation_errors());
        }
        // If the data entered is correct.
        return $this->response_success(['user' => $user], 200);
    }

    /**
     * Logout the user.
     *
     */
    public function logout()
    {
        Login_model::logout();
        redirect(base_url('/'));
    }

    public function add_money()
    {
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }

    public function buy_boosterpack()
    {
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }


    public function like()
    {
        // todo: add like post\comment logic
        return $this->response_success(['likes' => rand(1,55)]); // Колво лайков под постом \ комментарием чтобы обновить
    }

}
