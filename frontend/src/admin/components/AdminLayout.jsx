import AdminSidebar from './AdminSidebar';
import AdminTopbar from './AdminTopbar';
import { useLocation } from 'react-router-dom';

const TITLES = {
  '/admin/dashboard': 'Dashboard',
  '/admin/chapters':  'Chapters',
  '/admin/users':     'Users',
};

function getTitle(pathname) {
  if (pathname.includes('/edit')) return 'Edit Chapter';
  if (pathname.includes('/new'))  return 'New Chapter';
  return TITLES[pathname] || 'Admin Panel';
}

export default function AdminLayout({ children }) {
  const { pathname } = useLocation();

  return (
    <div className="admin-layout">
      <AdminSidebar />
      <div className="admin-main" id="admin-main">
        <AdminTopbar title={getTitle(pathname)} />
        <main className="admin-content" id="main-content">
          {children}
        </main>
      </div>
    </div>
  );
}
