import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './hooks/useAuth';  // 認証状態を返すカスタムフック

import LoginPage from './pages/LoginPage';
// import RegisterPage from './pages/RegisterPage';
// import DashboardPage from './pages/DashboardPage';
// import Mypage from './pages/Mypage';

export default function App() {
  const { user } = useAuth(); // ログイン状態を管理（null なら未ログイン）
  return (
    // <h1>Hello React!</h1>
    // <BrowserRouter>
    //   <Routes>
    //     <Route path="/login" element={<LoginPage />} />
    //   </Routes>
    // </BrowserRouter>
    <BrowserRouter>
      <Routes>
        {/* 未ログイン時のみ */}
        {!user && (
          <>
            <Route path="/login" element={<LoginPage />} />
            {/* <Route path="/register" element={<RegisterPage />} /> */}
          </>
        )}

        {/* ログイン後のみ */}
        {user && (
          <>
            <Route path="/dashboard" element={<DashboardPage />} />
            <Route path="/mypage" element={<Mypage />} />
          </>
        )}
        {/* デフォルトルート */}
        <Route path="*" element={<Navigate to={user ? "/dashboard" : "/login"} />} />
      </Routes>
    </BrowserRouter>
  );

}

ReactDOM.createRoot(document.getElementById('app')).render(<App />);
