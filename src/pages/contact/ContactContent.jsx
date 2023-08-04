import React, { useState } from 'react';
import { Toaster, toast } from 'sonner';
import { PaperAirplaneIcon, EnvelopeIcon } from "@heroicons/react/24/outline";
import styles from '../../assets/styles/contactContent.module.css';
import Email from '../../services/Email';
import LoadingSpinner from '../../components/LoadingSpinner';

function ContactContent() {
    const [isLoading, setIsLoading] = useState(false);

    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState('');
    const [subject, setSubject] = useState('');
    const [isConsentGiven, setIsConsentGiven] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        const data = {
            name: name,
            email:email,
            content:message,
            subject:subject
        }
        try{
            if(!isConsentGiven){
                new error("Tienes que confirmar la casilla")
            }
            const send = await Email.PostEmail(data);
            toast.success(send.message);
            setName('');
            setEmail('');
            setMessage('');
            setSubject('');
        } catch(error){
            toast.error(error.message);
        }
        setIsLoading(false);
    };

    return (
        <main className ={styles.main}>
            <Toaster richColors position="top-center" />
            <section className ={styles.mainContainer}>
                <div className={styles.mainContainerContactForm}>
                    <div className={styles.informationPerson}>
                        <h1>¡Contactame!</h1>
                        <div className={styles.informationPersonEmail}>
                            <EnvelopeIcon className={`h-6 w-6 text-gray-500 mr-1`} />
                            <a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a>
                        </div>
                        <div className={styles.informationSocialMedia}>
                            <a href="https://www.twitter.com/luifereduardoo" target="_blank">
                                <svg xmlns="http://wwboo.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <title>ionicons-v5_logos</title>
                                    <path
                                        d="M496,109.5a201.8,201.8,0,0,1-56.55,15.3,97.51,97.51,0,0,0,43.33-53.6,197.74,197.74,0,0,1-62.56,23.5A99.14,99.14,0,0,0,348.31,64c-54.42,0-98.46,43.4-98.46,96.9a93.21,93.21,0,0,0,2.54,22.1,280.7,280.7,0,0,1-203-101.3A95.69,95.69,0,0,0,36,130.4C36,164,53.53,193.7,80,211.1A97.5,97.5,0,0,1,35.22,199v1.2c0,47,34,86.1,79,95a100.76,100.76,0,0,1-25.94,3.4,94.38,94.38,0,0,1-18.51-1.8c12.51,38.5,48.92,66.5,92.05,67.3A199.59,199.59,0,0,1,39.5,405.6,203,203,0,0,1,16,404.2,278.68,278.68,0,0,0,166.74,448c181.36,0,280.44-147.7,280.44-275.8,0-4.2-.11-8.4-.31-12.5A198.48,198.48,0,0,0,496,109.5Z"
                                        fill="white"
                                    />
                                </svg>
                            </a>
                            <a href="https://www.instagram.com/luifereduardoo" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" className="bi bi-instagram" viewBox="0 0 16 16">
                                    <path 
                                        d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"
                                        fill="white"
                                    />
                                    </svg>
                            </a>
                            <a href="https://www.facebook.com/luifereduardoo" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" className="bi bi-facebook" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" fill="white">
                                    </path>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" className="bi bi-linkedin" viewBox="0 0 16 16">
                                    <path 
                                        d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"
                                        fill="white"
                                    />
                                </svg>
                            </a>
                            <a href="https://github.com/LuiferEduardoo" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" className="bi bi-github" viewBox="0 0 16 16"> 
                                    <path 
                                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" 
                                        fill="white">
                                    </path> 
                                </svg>
                            </a>
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
                            type="text"
                            id="subject"
                            value={subject}
                            placeholder='Asunto'
                            onChange={(e) => setSubject(e.target.value)}
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
                        <label htmlFor="consent" className={styles.labelConsent}>
                            <input
                                type="checkbox"
                                checked={isConsentGiven}
                                onChange={(e)=> setIsConsentGiven(e.target.checked)}
                                required
                            />
                            Acepto que mi información se guarde. 
                        </label>
                        <button 
                            type="submit" 
                            className={styles.button}
                            disabled={isLoading}
                            >
                            {isLoading ? (
                                <span>
                                    <LoadingSpinner size={20} color="#fff" className="mr-2" />
                                    <span className="ml-2">Processing...</span>
                                </span>
                                ) : (
                                <>
                                    Enviar
                                </>
                                )}
                        </button>
                    </form>
                </div>
            </section>
        </main>
    );
}
export {ContactContent};