import React, { useState } from 'react';
import styles from '../../assets/styles/login.module.css';
import { Toaster, toast } from 'sonner';
import AuthService from '../../services/AuthService';
import Cookies from 'js-cookie';
import {dataEncrypt} from '../../utils/data-encrypt';


function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };

    const handlePasswordChange = (e) => {
        setPassword(e.target.value);
    };

    const handleLoginFormSubmit = async (e) => {
        e.preventDefault();
        try {
            const token = await AuthService.login(email, password);
            const encryptedToken = dataEncrypt(token); 
            Cookies.set('token', encryptedToken);
            window.location.href = '/administration-panel/content/skills';
        } catch (error) {
            toast.error(error.message);
        }
    };
    return (
        <main className ={styles.main}>
            <Toaster richColors position="top-center" />
            <section className ={styles.mainContainer}>
                <div className={styles.mainContainerLogin}>
                    <div className={styles.mainContainerForm}>
                        <h2>Login</h2>
                        <form onSubmit={handleLoginFormSubmit}>
                            <div>
                            <input 
                                type="email"
                                id="email"
                                placeholder='Email'
                                value={email}
                                onChange={handleEmailChange}
                                required
                                />
                            </div>
                            <div>
                            <input 
                                type="password" 
                                id="password"
                                placeholder='Password'
                                value={password}
                                onChange={handlePasswordChange}
                                required
                            />
                            </div>
                            <button type="submit">Iniciar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    );
}

export {Login};