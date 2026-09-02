import { createContext, useContext, useState, useCallback } from 'react';
import axios from 'axios';
const AuthContext = createContext(null);

const API_BASE = import.meta.env.VITE_API_URL || 'https://bhaktachintamani.freedev.app';
const ADMIN_PHP = `${API_BASE}/api_admin.php`;
const API = `${API_BASE}/api/admin`;

export function AuthProvider({ children }) {
  const [admin, setAdmin] = useState(() => {
    try {
      const s = sessionStorage.getItem('rv-admin');
      return s ? JSON.parse(s) : null;
    } catch { return null; }
  });

  const [token, setToken] = useState(
    () => sessionStorage.getItem('rv-token') || null
  );

  const login = useCallback(async (email, password) => {
    let res;
    let lastError;

    const isHTML = (d) => typeof d === 'string' && (d.trim().startsWith('<!doctype') || d.trim().startsWith('<html'));

    // 1. Try Laravel API first
    try {
      const r = await axios.post(`${API}/login`, { email, password });
      if (r.data && !isHTML(r.data)) res = r;
    } catch (err) {
      lastError = err;
    }

    // 2. Fallback to direct PHP script
    if (!res) {
      try {
        const r = await axios.post(`${ADMIN_PHP}?action=login`, { email, password });
        if (r.data && !isHTML(r.data)) res = r;
      } catch (err) {
        lastError = err;
      }
    }

    // 3. Fallback to relative /api_admin.php
    if (!res) {
      try {
        const r = await axios.post(`/api_admin.php?action=login`, { email, password });
        if (r.data && !isHTML(r.data)) res = r;
      } catch (err) {
        lastError = err;
      }
    }

    // 4. Fallback to /public/api_admin.php
    if (!res) {
      try {
        const r = await axios.post(`/public/api_admin.php?action=login`, { email, password });
        if (r.data && !isHTML(r.data)) res = r;
      } catch (err) {
        lastError = err;
      }
    }

    if (!res || !res.data) {
      const msg = lastError?.response?.data?.message || lastError?.message || 'Login failed. Please try again.';
      throw new Error(msg);
    }

    let data = res.data;
    if (typeof data === 'string') {
      try {
        // Strip any unexpected HTML or trailing scripts added by free host
        const jsonStart = data.indexOf('{');
        const jsonEnd = data.lastIndexOf('}');
        if (jsonStart !== -1 && jsonEnd !== -1) {
          data = JSON.parse(data.substring(jsonStart, jsonEnd + 1));
        }
      } catch (e) {
        console.error('Failed to parse JSON response:', e, data);
      }
    }

    const t = data?.token;
    const a = data?.admin;

    if (!t || !a) {
      console.error('Received server response:', data);
      throw new Error(data?.message || 'Invalid response from server. Please try again.');
    }
    setToken(t);
    setAdmin(a);
    sessionStorage.setItem('rv-token', t);
    sessionStorage.setItem('rv-admin', JSON.stringify(a));
    return a;
  }, []);

  const logout = useCallback(async () => {
    try {
      if (token) {
        await axios.post(`${API}/logout`, {}, {
          headers: { Authorization: `Bearer ${token}` },
        }).catch(() => {
          return axios.post(`${API_BASE}/api_admin.php?action=logout`, {}, {
            headers: { Authorization: `Bearer ${token}` },
          });
        });
      }
    } catch { /* ignore */ }
    setToken(null);
    setAdmin(null);
    sessionStorage.removeItem('rv-token');
    sessionStorage.removeItem('rv-admin');
  }, [token]);

  const hasPermission = useCallback((perm) => {
    if (!admin) return false;
    if (admin.is_super_admin) return true;
    return !!(admin.permissions?.[perm]);
  }, [admin]);

  // Axios helper with auth header
  const authAxios = useCallback(() => {
    const instance = axios.create({
      baseURL: `${API_BASE}/api/admin`,
      headers: { Authorization: `Bearer ${token}` },
    });
    instance.interceptors.response.use(
      res => res,
      err => {
        if (err.response && err.response.status === 401) {
          sessionStorage.removeItem('rv-token');
          sessionStorage.removeItem('rv-admin');
        }
        return Promise.reject(err);
      }
    );
    return instance;
  }, [token]);

  return (
    <AuthContext.Provider value={{ admin, token, login, logout, hasPermission, authAxios }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used inside AuthProvider');
  return ctx;
}
