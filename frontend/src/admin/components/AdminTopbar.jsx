import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import { IconSun, IconMoon, IconGlobe, IconUser, IconLogOut } from '../../components/Icons';

export default function AdminTopbar({ title }) {
  const { admin, logout } = useAuth();
  const { theme, toggleTheme } = useTheme();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/admin/login');
  };

  return (
    <header className="admin-topbar" role="banner">
      <div className="topbar-left">
        <h1 className="topbar-title">{title}</h1>
      </div>

      <div className="topbar-right">
        {/* Theme toggle */}
        <button
          id="admin-theme-toggle"
          className="btn btn-icon btn-ghost"
          onClick={toggleTheme}
          aria-label={`Switch to ${theme === 'dark' ? 'light' : 'dark'} theme`}
          title="Toggle theme"
        >
          {theme === 'dark' ? (
            <IconSun size={18} color="var(--accent)" />
          ) : (
            <IconMoon size={18} color="var(--text-primary)" />
          )}
        </button>

        {/* View public site */}
        <Link
          to="/"
          target="_blank"
          rel="noopener noreferrer"
          className="btn btn-ghost btn-sm"
          title="View public site"
        >
          <IconGlobe size={16} /> View Site
        </Link>

        {/* Admin info + logout */}
        <div className="topbar-admin">
          <IconUser size={16} />
          <span>{admin?.name}</span>
          {admin?.is_super_admin && (
            <span className="topbar-admin-badge">Super Admin</span>
          )}
        </div>

        <button
          id="admin-logout-btn"
          className="btn btn-ghost btn-sm"
          onClick={handleLogout}
        >
          <IconLogOut size={16} /> Logout
        </button>
      </div>
    </header>
  );
}
