import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import PermissionGate from '../components/PermissionGate';
import { IconBook, IconCheck, IconFileText, IconUsers, IconPlus, IconGlobe } from '../../components/Icons';

export default function DashboardPage() {
  const { authAxios } = useAuth();
  const [stats, setStats]     = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const ax = authAxios();
    Promise.all([
      ax.get('/chapters?limit=1&status=published'),
      ax.get('/chapters?limit=1&status=draft'),
      ax.get('/chapters?limit=1'),
      ax.get('/users').catch(() => ({ data: [] })),
    ]).then(([pub, draft, all, users]) => {
      setStats({
        published: pub.data.total,
        draft:     draft.data.total,
        total:     all.data.total,
        users:     Array.isArray(users.data) ? users.data.length : 0,
      });
    }).catch(() => {
      setStats({ published: 0, draft: 0, total: 0, users: 0 });
    }).finally(() => setLoading(false));
  }, [authAxios]);

  const STAT_CARDS = [
    { icon: IconBook,     label: 'Total Chapters', key: 'total',     link: '/admin/chapters' },
    { icon: IconCheck,    label: 'Published',      key: 'published', link: '/admin/chapters?status=published' },
    { icon: IconFileText, label: 'Drafts',         key: 'draft',     link: '/admin/chapters?status=draft' },
    { icon: IconUsers,    label: 'Admin Users',    key: 'users',     link: '/admin/users' },
  ];

  return (
    <div>
      <div className="page-header">
        <div>
          <h1>Dashboard</h1>
          <p>Overview of your reading platform</p>
        </div>
        <PermissionGate perm="manage_chapters">
          <Link to="/admin/chapters/new" className="btn btn-primary" id="new-chapter-btn">
            <IconPlus size={16} /> New Chapter
          </Link>
        </PermissionGate>
      </div>

      <div className="dashboard-stats">
        {STAT_CARDS.map(card => {
          const Icon = card.icon;
          return (
            <Link
              to={card.link}
              key={card.key}
              className="stat-card"
              id={`stat-${card.key}`}
              style={{ textDecoration: 'none' }}
            >
              <span className="stat-icon"><Icon size={28} color="var(--accent)" /></span>
              <span className="stat-value">
                {loading ? '—' : (stats?.[card.key] ?? 0)}
              </span>
              <span className="stat-label">{card.label}</span>
            </Link>
          );
        })}
      </div>

      <div style={{
        background: 'var(--bg-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        padding: 'var(--space-xl)',
        marginTop: 'var(--space-xl)',
      }}>
        <h2 style={{ fontSize: '1rem', fontWeight: 700, marginBottom: 'var(--space-md)', color: 'var(--text-primary)' }}>
          Quick Actions
        </h2>
        <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
          <PermissionGate perm="manage_chapters">
            <Link to="/admin/chapters/new" className="btn btn-primary">
              <IconPlus size={16} /> New Chapter
            </Link>
            <Link to="/admin/chapters" className="btn btn-secondary">
              <IconBook size={16} /> Manage Chapters
            </Link>
          </PermissionGate>
          <PermissionGate perm="manage_users">
            <Link to="/admin/users" className="btn btn-secondary">
              <IconUsers size={16} /> Manage Users
            </Link>
          </PermissionGate>
          <a href="http://localhost:5173" target="_blank" rel="noopener noreferrer" className="btn btn-ghost">
            <IconGlobe size={16} /> View Public Site
          </a>
        </div>
      </div>
    </div>
  );
}
