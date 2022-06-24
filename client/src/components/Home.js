import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

function Home(props) {
    const navigation = useNavigate();
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [password2, setPassword2] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [userId, setUserId] = React.useState('');

    const usernameChange = (e) => setUsername(e.target.value);
    const passwordChange = (e) => setPassword(e.target.value);
    const emailChange = (e) => setEmail(e.target.value);
    const password2Change = (e) => setPassword2(e.target.value);

    const update = async (e) => {
        e.preventDefault();
       await fetch('http://127.0.0.1/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ userId, username,email, password })
        }).then(res => res.json())
            .then(data => {
                if (data.error) {
                    toast.error(data.error);
                } else {
                    toast.success(data.message);
                    localStorage.setItem('userData', JSON.stringify(data));
                }
            }).catch(err => console.log(err));
    }

    const logout = () => {
        fetch('http://127.0.0.1/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ userId })
        }).then(res => res.json())
            .then(data => {
                if (data.error) {
                    toast.error(data.error);
                } else {
                    toast.success(data.message);
                    localStorage.removeItem('userData');
                    localStorage.removeItem('isLoggedIn');
                    navigation('/login');
                }
            }).catch(err => console.log(err));
    };

    useEffect(() => {
        if (!localStorage.getItem('isLoggedIn')) {
            navigation('/login');
        }
    });

    useEffect(() => {
        const user = JSON.parse(localStorage.getItem('userData'));
        if (user) {
            setUsername(user.username);
            setEmail(user.email);
            setPassword(user.password);
            setUserId(user.id);
        }
    }, []);

    return (
        <div className="main">
            <section className="signup">
                <div className="container">
                    <div className="signup-content">
                        <div className="signup-form">
                            <h2 className="form-title">Profile {userId}</h2>
                            <form onSubmit={update} className="register-form" id="register-form">
                                <div className="form-group">
                                    <label htmlFor="name"><i
                                        className="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" name="name" id="name" placeholder="Your Name"
                                           defaultValue={ username } onChange={usernameChange}/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="email"><i className="zmdi zmdi-email"></i></label>
                                    <input type="email" name="email" id="email" placeholder="Your Email"
                                           defaultValue={ email } onChange={emailChange}/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="pass"><i className="zmdi zmdi-lock"></i></label>
                                    <input type="password" onChange={passwordChange} name="pass" id="pass" defaultValue={ password }
                                           placeholder="********"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="re-pass"><i className="zmdi zmdi-lock-outline"></i></label>
                                    <input type="password" onChange={password2Change} name="re_pass" defaultValue={ password } id="re_pass"
                                           placeholder="********"/>
                                </div>
                                <div className="form-group form-button">
                                    <input type="submit" name="save" id="save" className="form-submit" value="Save"/>
                                </div>
                            </form>
                        </div>
                        <div className="signup-image">
                            <figure><img src="/assets/images/signup-image.jpg" alt="sing up image"/></figure>
                            <a href="#" onClick={ () => logout() } className="signup-image-link">Logout</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default Home;