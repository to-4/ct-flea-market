// ./hooks/useAuth.js
import { useEffect, useState } from 'react';
import axios from 'axios';

export function useAuth() {
  const [user, setUser] = useState(null);     // ユーザー情報（ログインしていなければ null）
  const [loading, setLoading] = useState(true); // ロード中フラグ

  // ログイン中のユーザー情報を取得
  useEffect(() => {
    axios.get('/api/user')
      .then(response => {
        setUser(response.data);    // ユーザー情報を保存
      })
      .catch(() => {
        setUser(null);             // 未ログイン時は null
      })
      .finally(() => {
        setLoading(false);         // ロード終了
      });
  }, []);

  return { user, loading };
}
