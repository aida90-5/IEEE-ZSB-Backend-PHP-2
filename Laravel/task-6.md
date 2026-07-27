# HTTP
```txt

---------------------------------------------------------
| Layer 7: Application Layer (HTTP, HTTPS, FTP, SSH, DNS)   |  <-- Backend Engineers focus here!
---------------------------------------------------------
| Layer 6: Presentation Layer (SSL/TLS, Encryption)         |
---------------------------------------------------------
| Layer 5: Session Layer (RPC, NetBIOS)                     |
---------------------------------------------------------
| Layer 4: Transport Layer (TCP, UDP)                       |  <-- Connection setup 
---------------------------------------------------------
| Layer 3: Network Layer (IP, ICMP)                         |
---------------------------------------------------------
| Layer 2: Data Link Layer (Ethernet, MAC)                  |
---------------------------------------------------------
| Layer 1: Physical Layer (Fiber, Cables, Signals)          |
---------------------------------------------------------

```
### Key Takeaways:
- **Application Layer (Layer 7):** HTTP resides at Layer 7 Backend software engineers primarily build business logic and API contracts at this layer.
- **Reliable Transport requirement:** HTTP requires a reliable underlying transport layer that guarantees message ordering and prevents loss.
- **TCP (Transmission Control Protocol):** Connection-oriented protocol using a **3-way handshake** (SYN, SYN-ACK, ACK) to establish reliable connections before data transmission.

---
| Version | Transport Protocol | Key Features & Architectural Changes | Drawbacks / Limitations |
| :--- | :--- | :--- | :--- |
| **HTTP/1.0** | TCP | Opens a brand-new TCP connection for *every single* request/response cycle. | Extreme overhead and high latency due to constant TCP handshake setups/teardowns  |
| **HTTP/1.1** | TCP | Introduced **Persistent Connections** (reuse same TCP connection), Chunked Transfer Encoding, pipelining, and better caching headers | Subject to **Head-of-Line (HOL) Blocking** at the HTTP level if one request blocks the pipeline. |
| **HTTP/2.0**  | TCP | Introduced **Multiplexing** over a single TCP connection, **Binary Framing Layer** (instead of plain text), Header Compression (**HPACK**), and **Server Push**  | Still vulnerable to TCP-level Head-of-Line blocking if packet loss occurs in the TCP connection |
| **HTTP/3.0** | **QUIC (over UDP)** | Built on top of **QUIC** (UDP-based transport protocol). Eliminates TCP Head-of-Line blocking, enables ultra-fast connection setup, and seamless connection migration. | High CPU overhead on legacy hardware; UDP blocking on strict enterprise firewalls. |
---
# HTTP Request Message Structure 
```http
POST /api/v1/users HTTP/1.1
Host: api.example.com
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
Content-Type: application/json
Content-Length: 48

{
  "username": "backend_dev",
  "role": "engineer"
}
```

# HTTP Response Message Structure 
```HTTP
HTTP/1.1 200 OK
Date: Mon, 27 Jul 2026 17:58:00 GMT
Content-Type: application/json; charset=utf-8
Content-Length: 53
Cache-Control: max-age=10

{
  "status": "success",
  "message": "User created successfully"
}
```

```txt
                        ----------------------------------
                       |        HTTP Header Types         |
                        ----------------------------------
                                        |
      ------------------------------------------------------------------------
      |                 |               |                 |                  |
--------------   --------------   --------------    --------------      ---------------
|  Request   |   |  Response  |   |  General   |   |Representation|     |   Security   |
|  Headers   |   |  Headers   |   |  Headers   |   |   Headers    |     |    Headers   |
--------------   --------------   --------------    ---------------      ---------------
```
# HTTP Methods & Idempotency
| Method | Intended Action | Has Request Body? | Idempotent? | Safe? (Read-Only) |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | Fetch/Retrieve resources without side effects. | No | Yes | Yes |
| **POST** | Create a new resource or execute processing. | Yes | No | No |
| **PUT** | Replace a resource target completely. | Yes | Yes | No |
| **PATCH** | Apply partial modifications to a resource. | Yes | No (typically) | No |
| **DELETE** | Remove the specified resource. | Optional | Yes | No |
| **OPTIONS** | Inspect server communication capabilities/CORS. | No | Yes | Yes |

---
# Evolution of HTTP 
```txt
  HTTP/1.0                  HTTP/1.1                  HTTP/2                    HTTP/3
------------              ------------              ------------              ------------
|  TCP     | (1 conn/req) |  TCP     | (Persistent) |  TCP     | (Multiplexed)|  UDP     | (QUIC)
------------              ------------              ------------              ------------
|  Text    |              |  Text    |              |  Binary  |              |  Binary  |
------------              ------------              ------------              ------------
```

1. **HTTP/1.0:** Short-Lived Connections
- Every single HTTP request/response required a brand-new TCP handshake (3-way handshake) and TLS setup.
- Massive overhead and network latency.

2. **HTTP/1.1:** Persistent Connections & Pipelining
- Persistent Connections (Keep-Alive): Reuses the same underlying TCP connection for multiple sequential requests.
- Head-of-Line (HOL) Blocking (Application Layer): Requests are processed strictly sequentially. If Request #1 hangs or takes long to process, Requests #2 and #3 are blocked behind it on the same TCP socket.

3. **HTTP/2:** Binary Framing & Multiplexing
- Binary Framing: Messages are split into smaller, typed frames (DATA, HEADERS).
- Multiplexing: Multiple requests and responses stream concurrently over a single shared TCP connection.
- Header Compression (HPACK): Cuts down HTTP header overhead.
- TCP-level HOL Blocking: If a packet drops at the transport layer (TCP), all HTTP streams on that connection are blocked until the dropped packet is retransmitted.

4. **HTTP/3:** QUIC over UDP
- Replaces TCP with QUIC (a UDP-based transport protocol).
- Eliminates TCP-level Head-of-Line blocking: A dropped packet on Stream A does not stall data processing on Stream B.
- Faster connection setup combining transport and cryptographic handshakes into 0-RTT or 1-RTT.

---
# Serialization 
* **Serialization (Marshaling):** The process of translating dynamic, in-memory data structures (like objects, maps, or trees) into a flat, sequential format (e.g., JSON, Protocol Buffers, Byte Arrays) for storage or network transmission.
* **Deserialization (Unmarshaling):** The reverse process—reconstructing a structured in-memory object from a serialized stream of bytes or text.

## Common Formats Compared

| Format | Type | Human-Readable? | Schema Required? | Relative Size / Speed | Primary Use Cases |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **JSON** | Text | **Yes** | No (Optional via JSON Schema) | Large / Moderate | REST APIs, Web/Mobile Integration |
| **Protocol Buffers (Protobuf)** | Binary | **No** | **Yes** (`.proto`) | Small / Extremely Fast | gRPC, Microservices, High-throughput IPC |
| **Avro** | Binary | **No** | **Yes** (JSON-based) | Compact / Fast | Apache Kafka, Big Data Pipelines |
| **MessagePack** | Binary | **No** | No | Medium / Fast | Cache storage (Redis), lightweight IPC |
| **XML** | Text | **Yes** | Optional (XSD) | Very Large / Slow | Legacy systems, SOAP APIs, Enterprise Configs |


## 1. Security Risks (Deserialization Vulnerabilities)

>  **Security Warning:** Never deserialize arbitrary bytes into complex objects from untrusted sources. Attackers can leverage polymorphic types to trigger **Remote Code Execution (RCE)** or Denial of Service (DoS).

* **Mitigation:**
  * Use simple data transfer objects (DTOs) instead of native language object streams (e.g., avoid Python's `pickle` or Java's native `ObjectInputStream` on untrusted input).
  * Validate and sanitize all payload structures **after** deserialization.
  * Implement strict type allowlists.



## 2. Backward & Forward Compatibility
* **Additive Changes:** Adding optional fields is usually safe across most serialization formats.
* **Breaking Changes:** Renaming fields, changing field data types, or deleting fields breaks backward compatibility.
* **Best Practices for Schema Evolution:**
  * Use explicit field tags/IDs (like Protobuf numeric field tags) rather than relying on field order.
  * Assign default values to new fields.

## 3. Performance & Memory Management
* **CPU Overhead:** Parsing large text-based formats (like JSON/XML) requires significant string parsing and memory allocations.
* **Bandwidth & Storage:** Binary formats reduce payload size drastically, reducing network IO overhead in high-throughput environments.
* **Zero-Copy Serialization:** Advanced frameworks (e.g., FlatBuffers, Cap'n Proto) allow accessing data directly from memory buffers without explicit deserialization.

---

## 💻 Code Example
```typescript
import { z } from "zod";

// 1. Define schema for runtime validation during deserialization
const UserSchema = z.object({
  id: z.string().uuid(),
  username: z.string().min(3),
  email: z.string().email(),
  role: z.enum(["admin", "user"]).default("user"),
});

type User = z.infer<typeof UserSchema>;

// 2. Safe Deserialization Function
function safeDeserializeUser(jsonString: string): User {
  // Parse JSON string to raw JS Object
  const rawData = JSON.parse(jsonString);

  // Validate and type-cast safely
  return UserSchema.parse(rawData);
}

// 3. Serialization
function serializeUser(user: User): string {
  return JSON.stringify(user);
}
```
#  Caching

- Caching is the practice of storing copies of data in a fast, temporary storage layer (usually RAM) so that future requests for that data can be served faster than fetching it from the primary source.


## Why Caching Matters

* **Reduces Latency:** RAM access speeds (nanoseconds) dwarf disk or network calls (milliseconds).
* **Decreases Database Load:** Prevents expensive SQL joins, complex queries, and read locks on primary databases.
* **Cost Efficiency:** Offloads traffic from high-cost compute/database instances to lighter key-value stores.
* **Improves Availability:** Serves stale cached content if the downstream database or service experiences a temporary outage.



##  Key Caching Strategies


| Strategy | Read Path | Write Path | Pros | Cons |
| :--- | :--- | :--- | :--- | :--- |
| **Cache-Aside (Lazy Loading)** | Application reads from Cache. On miss, reads from DB and updates Cache. | Application writes directly to DB. | Cache only contains data that is actively requested. | Cache misses incur latency overhead. |
| **Read-Through** | Application requests data from Cache. Cache fetches from DB on miss and returns it. | Application writes directly to DB. | Simplifies application code logic. | Cache miss penalty remains. |
| **Write-Through** | Read directly from Cache. | Application writes to Cache; Cache synchronously writes to DB. | Cache is never stale; high data consistency. | Write latency is higher (two synchronous writes). |
| **Write-Back (Write-Behind)** | Read directly from Cache. | Application writes to Cache. Cache asynchronously batches writes to DB. | Extremely high write performance and throughput. | Risk of data loss if the cache node crashes before flushing to DB. |

---

##  Cache Eviction Policies
* **LRU (Least Recently Used):** Discards the items that haven't been accessed for the longest time. *(Most common default)*
* **LFU (Least Frequently Used):** Discards items with the lowest access count.
* **FIFO (First In, First Out):** Discards the oldest entries regardless of how often or recently they were accessed.
* **TTL (Time-To-Live):** Data automatically expires after a fixed duration (e.g., `3600 seconds`).

##  Common Pitfalls & Edge Cases

## Cache Stampede (Thundering Herd Problem)
Occurs when a popular cached item expires, and thousands of concurrent requests miss the cache at the same time—all hitting the underlying database simultaneously.
* **Solution:** Implement **mutex locks** so only one worker populates the cache, or use **probabilistic early expiration**.

## Cache Penetration
Occurs when requests continuously query non-existent keys, causing every request to bypass the cache and hit the database.
* **Solution:** Cache empty/null results with a short TTL, or use a **Bloom Filter** to validate key existence before querying.

## Cache Avalanche
Occurs when many cached keys expire at the exact same instant, causing a sudden surge of database traffic.
* **Solution:** Add random **jitter** (e.g., ±5 minutes) to expiration times so keys expire asynchronously.

---

## 💻 Code Example

```typescript
import { createClient } from "redis";

const redis = createClient();
const CACHE_TTL_SECONDS = 300; // 5 minutes

interface UserProfile {
  id: string;
  name: string;
}

async function getUserProfile(userId: string): Promise<UserProfile> {
  const cacheKey = `user:${userId}`;

  // 1. Check Redis Cache
  const cachedData = await redis.get(cacheKey);
  if (cachedData) {
    return JSON.parse(cachedData); // Cache Hit
  }

  // 2. Cache Miss: Fetch from Primary Database
  const user = await db.users.findById(userId);

  // 3. Populate Cache with TTL
  if (user) {
    await redis.setEx(cacheKey, CACHE_TTL_SECONDS, JSON.stringify(user));
  }

  return user;
}
```

# UML
```text
+------------------------------------+
|             ClassName              |  <-- Top: Class Name
+------------------------------------+
| - privateAttribute: string        |  <-- Middle: Attributes (State)
| # protectedAttribute: number       |
| + publicAttribute: boolean         |
+------------------------------------+
| + publicMethod(): void             |  <-- Bottom: Methods (Behavior)
| - privateMethod(param: int): bool  |
+------------------------------------+
```
- **Public:** Accessible from any other class.
- **Private:** Accessible only within the defining class.
- **Protected:** Accessible within the class and its derived subclasses.
- **Package/Internal:** Accessible only within the same package or module.

## Code
```typescript
interface IDatabase {
  connect(): void;
}

class User {
  private userId: string;
  public email: string;

  constructor(id: string, email: string) {
    this.userId = id;
    this.email = email;
  }
}

class Order {
  private id: string;
  private items: OrderItem[] = []; // Composition

  public addItem(item: OrderItem): void {
    this.items.push(item);
  }
}

class OrderItem {
  constructor(public productName: string, public price: number) {}
}
```

---
```c++
classDiagram
    class IDatabase {
        <<interface>>
        +connect() void
    }

    class User {
        -String userId
        +String email
        +getUserDetails() User
    }

    class Order {
        -String id
        -List~OrderItem~ items
        +addItem(OrderItem item) void
    }

    class OrderItem {
        +String productName
        +Float price
    }

    %% Relationships
    Order "1" *-- "many" OrderItem : Composition
    User "1" --> "many" Order : Places
    PostgresDriver ..|> IDatabase : Realization
```
---
# Observer Pattern | Ultimate Design Patterns Guide
# Introduction

- The **Observer Pattern** is one of the foundational **Behavioral Design Patterns**.
- It is used to establish a **One-to-Many** dependency between objects so that when one object (known as the **Subject** or **Publisher**) changes its state. all its dependents (known as **Observers** or **Subscribers**) are automatically notified and updated.


## The Problem

Imagine you have two objects: a `Customer` and a `Store`.
- The customer is highly interested in a specific product (e.g., a new iPhone) that will be released soon.
- **Naive Approach 1 (Polling):** The customer repeatedly visits or calls the store every day to check if the product has arrived. This wastes tremendous time and computing/human resources.
- **Naive Approach 2 (Spamming):** The store sends an email notification to every single customer in its database whenever *any* new product arrives. This annoys customers who have no interest in that particular product.

### The Result:
**Tight Coupling** between the store and customers, wasted bandwidth/resources on constant polling, and poor user experience.

##  The Solution

The Observer Pattern proposes adding a **Subscription Mechanism** to the primary object (the Subject):
1. The object holding the important state is called the **Subject** (or **Publisher**).
2. Objects that want to track state changes are called **Observers** (or **Subscribers**).
3. The Subject maintains a list of subscribers and provides core management methods:
   - `attach(observer)` / `subscribe(observer)`: Adds a new observer to the list.
   - `detach(observer)` / `unsubscribe(observer)`: Removes an observer from the list.
   - `notify()`: Iterates through the list and invokes the `update()` method on each observer when a state change occurs.

```txt
---------------------                  ---------------------
|     Subject       |                  |     Observer      |
---------------------                  ---------------------
| + attach(o)       |                  | + update()        |
| + detach(o)       |                  ---------------------
| + notify()        |                            ▲
---------------------                            |
          ▲                                      |
          | (inherits)               (inherits)  |
---------------------                  ---------------------
|  ConcreteSubject  |=================>| ConcreteObserver  |
---------------------   notifies       ---------------------
| - state           |                  | + update()        |
| + getState()      |                  ---------------------
| + setState()      |
---------------------
```
1. **Subject (Publisher Interface):** Declares methods for attaching, detaching, and notifying observers.
2. **ConcreteSubject:** Stores the state of interest and sends notifications to observers when the state changes.
3. **Observer (Subscriber Interface):** Declares the `update()` interface method that the Subject calls.
4. **ConcreteObserver:** Implements the `Observer` interface and reacts to updates triggered by the Subject.


## Practical Implementation 
```python
from abc import ABC, abstractmethod
from typing import List

# 1. Observer Interface
class Observer(ABC):
    @abstractmethod
    def update(self, message: str) -> None:
        pass


# 2. Subject Interface
class Subject(ABC):
    @abstractmethod
    def attach(self, observer: Observer) -> None:
        pass

    @abstractmethod
    def detach(self, observer: Observer) -> None:
        pass

    @abstractmethod
    def notify(self) -> None:
        pass


# 3. Concrete Subject
class NewsAgency(Subject):
    def __init__(self):
        self._observers: List[Observer] = []
        self._latest_news: str = ""

    def attach(self, observer: Observer) -> None:
        if observer not in self._observers:
            self._observers.append(observer)
            print("Observer attached successfully.")

    def detach(self, observer: Observer) -> None:
        self._observers.remove(observer)
        print("Observer detached successfully.")

    def notify(self) -> None:
        for observer in self._observers:
            observer.update(self._latest_news)

    def add_news(self, news: str) -> None:
        self._latest_news = news
        print(f"\n[NewsAgency] Breaking News: {news}")
        self.notify()


# 4. Concrete Observers
class NewsChannel(Observer):
    def __init__(self, name: str):
        self.name = name

    def update(self, message: str) -> None:
        print(f"[{self.name}] Broadcasted News: '{message}'")


class MobileApp(Observer):
    def __init__(self, user_name: str):
        self.user_name = user_name

    def update(self, message: str) -> None:
        print(f"[Push Notification to {self.user_name}]: '{message}'")


if __name__ == "__main__":
    agency = NewsAgency()

    tv_channel = NewsChannel("CNN")
    user_app = MobileApp("John")

    # Subscribe observers
    agency.attach(tv_channel)
    agency.attach(user_app)

    # State change -> Notify all
    agency.add_news("Design Patterns Course Released!")

    # Unsubscribe one observer
    agency.detach(user_app)

    # State change -> Notify remaining observer
    agency.add_news("Observer Pattern simplifies Event-Driven Architecture!")
```

# Pros & Cons
**Pros:**
- Open/Closed Principle: You can introduce new observer classes without modifying the subject's code.
- Loose Coupling: The Subject only knows that observers implement a specific interface; it doesn't care about their concrete implementation details.
- Dynamic Relationships: Observers can be attached or detached dynamically at runtime.

**Cons:**
- Random Notification Order: Subscribers are typically notified in an arbitrary order.
- Memory Leaks (Lapsed Listener Problem): If you forget to detach an observer when it is no longer needed, it remains in memory and consumes resources.
- Performance Overhead: If there are many observers or if the update() operation is heavy, notifications can slow down execution.

--- 
* **Requirements Gathering & Discovery**
**Functional vs. Non-Functional Requirements:**
- Functional Requirements: What does the system do for the end user?
- Non-Functional Requirements: Quality attributes such as latency, availability, consistency, scalability, security, and maintainability.

**Business Motivations:**
- Understanding why a feature is being built and what tangible business value it brings.

**Asking the Right Questions:**
- Never rely on blind assumptions—continuously clarify edge cases with Product Managers and stakeholders.

* **Constraints & Estimations**
**Cost Estimation:**
- What is the cloud infrastructure cost compared to the expected revenue/benefit?

**Managing Uncertainty:**
- Building buffer time and fault tolerance to accommodate unknown variables.

**Scheduling Constraints:**
- Balancing quick releases (MVPs) against technical debt and architectural quality.

* **Diagrams & Documentation**
**Purpose of Diagramming:**
- Diagrams aren't for aesthetics; they bridge understanding gaps across cross-functional teams.
- Using structured standards like the C4 Model (Context, Containers, Components, Code) to present architecture at varying levels of detail.
