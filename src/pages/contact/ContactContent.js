import React, { useState } from 'react';
import styles from '../../assets/styles/contactContent.module.css';

function ContactContent() {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        // aquí puedes agregar la lógica para enviar el formulario
        console.log(`Nombre: ${name}\nEmail: ${email}\nMensaje: ${message}`);
    };

    return (
        <main className ={styles.main}>
            <section className ={styles.mainContainer}>
                <div className={styles.mainContainerContactForm}>
                    <div className={styles.informationPerson}>
                        <h1>¡Contactame!</h1>
                        <div className={styles.informationPersonEmail}>
                            <i className="las la-envelope-open-text"></i>
                            <a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a>
                        </div>
                        <div className={styles.informationSocialMedia}>
                            <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i className="lab la-twitter"></i></a>
                            <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i className="lab la-instagram"></i></a>
                            <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i className="lab la-facebook"></i></a>
                            <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i className="lab la-linkedin-in"></i></a>
                            <a href="https://github.com/LuiferEduardoo" target="_blank"><i className="lab la-github"></i></a>
                        </div>
                    </div>
                    <form onSubmit={handleSubmit} className={styles.contactForm}>
                        <h1>Formulario</h1>
                        <input
                            type="text"
                            id="name"
                            value={name}
                            placeholder='Nombre'
                            onChange={(e) => setName(e.target.value)}
                            required
                            className={styles.input}
                        />
                        <input
                            type="email"
                            id="email"
                            value={email}
                            placeholder='Correo electronico'
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            className={styles.input}
                        />
                        <input
                            type="email"
                            id="email"
                            value={email}
                            placeholder='Asunto'
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            className={styles.input}
                        />
                        <textarea
                            id="message"
                            value={message}
                            placeholder='Mensaje'
                            onChange={(e) => setMessage(e.target.value)}
                            required
                            className={styles.textarea}
                        />
                        <button type="submit" className={styles.button}>Enviar</button>
                    </form>
                </div>
            </section>
        </main>
    );
}
export {ContactContent};