import React from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

function Register(props) {
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [password2, setPassword2] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [error, setError] = React.useState('');

    const usernameChange = (e) => setUsername(e.target.value);
    const passwordChange = (e) => setPassword(e.target.value);
    const password2Change = (e) => setPassword2(e.target.value);
    const emailChange = (e) => setEmail(e.target.value);

    const navigation = useNavigate();

    function handleSubmit(e) {
        e.preventDefault();
        if (password !== password2) {
            toast.error('Passwords do not match');
        }


        if (username === '' || password === '' || password2 === '' || email === '') {
            toast.error('Please fill in all fields');
        }

        console.log(username, password, password2, email);

        fetch('http://127.0.0.1/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ username, email, password })
        }).then(res => res.json())
            .then(data => {
                if(data.error) {
                    toast.error(data.error);
                    setError(data.error);
                } else {
                    toast.success('Successfully registered');
                    navigation('/login');
                }
            })
            .catch(err => console.log(err));
    }

    return (
        <div className="main">
            <section className="signup">
                <div className="container">
                    <div className="signup-content">
                        <div className="signup-form">
                            <h2 className="form-title">Registration</h2>
                            <form onSubmit={ handleSubmit } className="register-form" id="register-form">
                                <div className="form-group">
                                    <label htmlFor="name"><i
                                        className="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" onChange={(e) => usernameChange(e)} name="name" id="name" placeholder="Your Name"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="email"><i className="zmdi zmdi-email"></i></label>
                                    <input type="email" onChange={(e) => emailChange(e)} name="email" id="email" placeholder="Your Email"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="pass"><i className="zmdi zmdi-lock"></i></label>
                                    <input type="password" onChange={(e) => passwordChange(e)} name="pass" id="pass" placeholder="Password"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="re-pass"><i className="zmdi zmdi-lock-outline"></i></label>
                                    <input type="password" onChange={(e) => password2Change(e)} name="re_pass" id="re_pass"
                                           placeholder="Repeat your password"/>
                                </div>
                                <div className="form-group">
                                    <input type="checkbox" name="agree-term" id="agree-term" className="agree-term"/>
                                    <label htmlFor="agree-term" className="label-agree-term"><span><span></span></span>I
                                        agree all statements in Terms of service</label>
                                </div>
                                <div className="form-group form-button">
                                    <input type="submit" name="signup" id="signup" className="form-submit"
                                           value="Register"/>
                                </div>
                            </form>
                        </div>
                        <div className="signup-image">
                            <figure><img src="/assets/images/signup-image.jpg" alt="sing up image"/></figure>
                            <a href="#!" onClick={ () => {
                                navigation('/login');
                            } } className="signup-image-link">I am already member</a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    );
}

export default Register;