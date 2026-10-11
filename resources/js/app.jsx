import { useState } from 'react';

function Mark({ small = false }) {
    return (
        <span className={`brand-mark${small ? ' brand-mark-small' : ''}`} aria-hidden="true">
            <svg viewBox="0 0 40 40" fill="none">
                <path d="M20 7v26M7 20h26" stroke="currentColor" strokeWidth="5" strokeLinecap="round" />
                <circle cx="20" cy="20" r="16" stroke="currentColor" strokeWidth="2" opacity=".35" />
            </svg>
        </span>
    );
}

function Field({ label, name, type = 'text', error, ...props }) {
    const message = Array.isArray(error) ? error[0] : error;

    return (
        <label className="field">
            <span className="field-label">{label}</span>
            <input
                name={name}
                type={type}
                autoComplete={name === 'password_confirmation' ? 'new-password' : name}
                aria-invalid={Boolean(message)}
                aria-describedby={message ? `${name}-error` : undefined}
                {...props}
            />
            {message && <span className="field-error" id={`${name}-error`}>{message}</span>}
        </label>
    );
}

function LoginForm({ errors, email }) {
    const [showPassword, setShowPassword] = useState(false);

    return (
        <form className="auth-form" method="post" action="/login">
            <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content} />
            <Field label="Email address" name="email" type="email" placeholder="you@example.com" defaultValue={email} error={errors.email} required autoComplete="email" autoFocus />
            <div className="password-wrap">
                <Field label="Password" name="password" type={showPassword ? 'text' : 'password'} placeholder="Enter your password" error={errors.password} required autoComplete="current-password" />
                <button className="password-toggle" type="button" onClick={() => setShowPassword((visible) => !visible)} aria-label={showPassword ? 'Hide password' : 'Show password'}>
                    {showPassword ? 'Hide' : 'Show'}
                </button>
            </div>
            <div className="form-options">
                <label className="check-label"><input type="checkbox" name="remember" value="1" /> <span>Remember me</span></label>
                <span className="muted-note">Secure sign in</span>
            </div>
            <button className="primary-button" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
    );
}

function RegisterForm({ errors, email }) {
    return (
        <form className="auth-form" method="post" action="/register">
            <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content} />
            <Field label="Full name" name="name" placeholder="Your name" error={errors.name} required autoComplete="name" autoFocus />
            <Field label="Email address" name="email" type="email" placeholder="you@example.com" defaultValue={email} error={errors.email} required autoComplete="email" />
            <Field label="Password" name="password" type="password" placeholder="At least 8 characters" error={errors.password} required autoComplete="new-password" />
            <Field label="Confirm password" name="password_confirmation" type="password" placeholder="Enter your password again" error={errors.password_confirmation} required />
            <button className="primary-button" type="submit">Create account <span aria-hidden="true">→</span></button>
        </form>
    );
}

function Dashboard({ name, status }) {
    return (
        <main className="dashboard-shell">
            <header className="topbar">
                <a className="brand" href="/"><Mark /><span>medz<span className="brand-accent">solutions</span></span></a>
                <form method="post" action="/logout">
                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content} />
                    <button className="logout-button" type="submit">Sign out <span aria-hidden="true">↗</span></button>
                </form>
            </header>
            <section className="dashboard-card">
                <div className="success-icon" aria-hidden="true">✓</div>
                <p className="eyebrow">YOU’RE SIGNED IN</p>
                <h1>Welcome, {name}.</h1>
                <p className="dashboard-copy">Your Laravel and React login flow is working. You can safely sign out whenever you’re ready.</p>
                {status && <p className="success-message" role="status">{status}</p>}
                <form method="post" action="/logout">
                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content} />
                    <button className="primary-button" type="submit">Sign out <span aria-hidden="true">→</span></button>
                </form>
            </section>
            <p className="footer-note">A simple, secure starting point for your next idea.</p>
        </main>
    );
}

export default function AuthApp({ mode, name, email, errors, status }) {
    if (mode === 'dashboard') {
        return <Dashboard name={name} status={status} />;
    }

    const registering = mode === 'register';

    return (
        <main className="auth-shell">
            <section className="welcome-panel">
                <a className="brand" href="/"><Mark /><span>medz<span className="brand-accent">solutions</span></span></a>
                <div className="welcome-copy">
                    <span className="welcome-tag"><span className="tag-dot" /> YOUR WORKSPACE, IN GOOD HANDS</span>
                    <h1>Care meets<br />clarity.</h1>
                    <p>A thoughtful space to manage your work, support your team, and keep moving forward.</p>
                    <div className="welcome-art" aria-hidden="true">
                        <div className="art-orbit orbit-one" />
                        <div className="art-orbit orbit-two" />
                        <div className="art-cross"><Mark /></div>
                        <div className="art-pill pill-one">✳ &nbsp; Made for people</div>
                        <div className="art-pill pill-two"><span className="tag-dot" /> All in one place</div>
                    </div>
                </div>
                <p className="panel-footnote">© 2026 MedzSolutions <span>Built with care.</span></p>
            </section>

            <section className="form-panel">
                <div className="form-topline">
                    <span>{registering ? 'NEW HERE?' : 'WELCOME BACK'}</span>
                    <a href={registering ? '/login' : '/register'}>{registering ? 'Sign in' : 'Create an account'} <span aria-hidden="true">↗</span></a>
                </div>
                <div className="form-content">
                    <div className="form-heading">
                        <div className="mobile-mark"><Mark small /></div>
                        <p className="eyebrow">{registering ? 'GET STARTED' : 'YOUR SPACE IS READY'}</p>
                        <h2>{registering ? 'Create your account' : 'Sign in to your account'}</h2>
                        <p>{registering ? 'A few details and you’re ready to go.' : 'Enter your details below to continue.'}</p>
                    </div>
                    {status && <p className="success-message" role="status">{status}</p>}
                    {registering ? <RegisterForm errors={errors} email={email} /> : <LoginForm errors={errors} email={email} />}
                    <p className="form-switch">
                        {registering ? 'Already have an account?' : 'New to MedzSolutions?'}{' '}
                        <a href={registering ? '/login' : '/register'}>{registering ? 'Sign in' : 'Create an account'}</a>
                    </p>
                    <p className="privacy-note"><span aria-hidden="true">♢</span> Protected with secure, session-based sign in.</p>
                </div>
                <p className="mobile-footer">© 2026 MedzSolutions</p>
            </section>
        </main>
    );
}
