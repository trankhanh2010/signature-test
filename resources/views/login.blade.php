<form action=""  method="post" >
    <label for="first">Email:</label>
          <input type="email" id="first" name="email" placeholder="Enter your Email" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your Password" required>
    <button type="submit">Login</button>
    @csrf
</form>