import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import { IconBook, IconSun, IconMoon, IconAlert } from '../../components/Icons';

export default function LoginPage() {
  const [email, setEmail]       = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading]   = useState(false);
  const [error, setError]       = useState('');
  const { login } = useAuth();
  const { theme, toggleTheme } = useTheme();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const admin = await login(email, password);
      if (admin.is_super_admin || admin.permissions?.view_dashboard) {
        navigate('/admin/dashboard');
      } else if (admin.permissions?.manage_chapters) {
        navigate('/admin/chapters');
      } else {
        navigate('/admin/dashboard');
      }
    } catch (err) {
      const msg = err.message
        || err.response?.data?.message
        || err.response?.data?.errors?.email?.[0]
        || 'Invalid credentials. Please try again.';
      setError(msg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-page">
      {/* Theme toggle */}
      <button
        id="login-theme-toggle"
        className="btn btn-icon btn-ghost"
        onClick={toggleTheme}
        aria-label="Toggle theme"
        style={{ position: 'fixed', top: '1.25rem', right: '1.25rem' }}
      >
        {theme === 'dark' ? <IconSun size={20} color="var(--accent)" /> : <IconMoon size={20} />}
      </button>

      <div className="login-card" role="main">
        <div className="login-card__logo">
          <div className="login-card__logo-icon" style={{ display: 'flex', justifyContent: 'center', marginBottom: '0.75rem' }}>
            <IconBook size={48} color="var(--accent)" />
          </div>
          <h1>Admin Panel</h1>
          <p>Bhakta-Chintamani Reading Platform</p>
        </div>

        {error && (
          <div className="form-error" role="alert" id="login-error" style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <IconAlert size={18} />
            <span>{error}</span>
          </div>
        )}

        <form className="login-form" onSubmit={handleSubmit} noValidate>
          <div className="form-group">
            <label className="form-label" htmlFor="login-email">Email Address</label>
            <input
              id="login-email"
              type="email"
              className="form-input"
              placeholder="admin@readverse.com"
              value={email}
              onChange={e => setEmail(e.target.value)}
              required
              autoComplete="email"
              autoFocus
            />
          </div>

          <div className="form-group">
            <label className="form-label" htmlFor="login-password">Password</label>
            <input
              id="login-password"
              type="password"
              className="form-input"
              placeholder="••••••••"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
              autoComplete="current-password"
            />
          </div>

          <button
            id="login-submit-btn"
            type="submit"
            className="btn btn-primary btn-lg login-submit"
            disabled={loading || !email || !password}
          >
            {loading ? (
              <>
                <span className="scroll-loader" style={{ width: 16, height: 16, borderWidth: 2 }} />
                Signing in…
              </>
            ) : (
              'Sign In'
            )}
          </button>
        </form>
      </div>
    </div>
  );
}
