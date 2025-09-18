import React, { useState } from "react";
import axios from "axios";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {

    e.preventDefault();
    try {

      await axios.post("/login", {
        email,
        password,
      });
      window.location.href = "/welcome"; // 認証後の遷移
    } catch (err) {
      setError("ログインに失敗しました");
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h1>ログイン</h1>
      {error && <div>{error}</div>}
      <div>
        <label>Email</label>
        <input value={email} onChange={(e) => setEmail(e.target.value)} />
      </div>
      <div>
        <label>Password</label>
        <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
      </div>
      <button type="submit">ログイン</button>
    </form>
  );
}

