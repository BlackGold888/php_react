import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

function Login() {
    const navigation = useNavigate();
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [remember, setRemember] = React.useState(false);
    const usernameChange = (e) => setUsername(e.target.value);
    const passwordChange = (e) => setPassword(e.target.value);

    const rememberChange = (e) => setRemember(e.target.checked);

    function handleSubmit(e) {
        e.preventDefault();
        if (username === '' || password === '') {
            toast.error('Please fill in all fields');
        }

        if (remember) {
            localStorage.setItem('username', username);
            localStorage.setItem('password', password);
            localStorage.setItem('remember', remember);
        }else{
            localStorage.removeItem('username');
            localStorage.removeItem('password');
            localStorage.removeItem('remember');
        }

        console.log(username, password);

        fetch('http://127.0.0.1/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ username, password })
        }).then(res => res.json())
            .then(data => {
                if (data.error) {
                    toast.error(data.error);
                } else {
                    localStorage.setItem('userData', JSON.stringify(data));
                    localStorage.setItem('isLoggedIn', true);
                    toast.success('Successfully logged in');
                    navigation('/');
                }
            })
            .catch(err => console.log(err));
    }

    useEffect(() => {
        if (localStorage.getItem('username')) {
            setUsername(localStorage.getItem('username'));
            setPassword(localStorage.getItem('password'));
            setRemember(localStorage.getItem('remember'));
        }
    }, []);

    return (
        <div className="main">
            <section className="sign-in">
                <div className="container">
                    <div className="signin-content">
                        <div className="signin-image">
                            <figure><img src="/assets/images/signin-image.jpg" alt="sing up image"/></figure>
                            <a href={ '#!' } onClick={ () => {
                                navigation('/register');
                            } } className="signup-image-link">Create an account</a>
                        </div>

                        <div className="signin-form">
                            <h2 className="form-title">Login</h2>
                            <form onSubmit={ handleSubmit } className="register-form" id="login-form">
                                <div className="form-group">
                                    <label htmlFor="your_name"><i className="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" onChange={ (e) => usernameChange(e) } defaultValue={ username }
                                           name="your_name" id="your_name" placeholder="Your Name"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="your_pass"><i className="zmdi zmdi-lock"></i></label>
                                    <input type="password" onChange={ (e) => passwordChange(e) }
                                           defaultValue={ password } name="your_pass" id="your_pass"
                                           placeholder="Password"/>
                                </div>
                                <div className="form-group">
                                    <input type="checkbox" onChange={ rememberChange } checked={remember} name="remember-me"
                                           id="remember-me" className="agree-term"/>
                                    <label htmlFor="remember-me" className="label-agree-term"><span><span></span></span>Remember
                                        me</label>
                                </div>
                                <div className="form-group form-button">
                                    <input type="submit" name="signin" id="signin" className="form-submit"
                                           value="Log in"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    );
}

export default Login;